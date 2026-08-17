<?php
// FILE: app/Services/ProductImageService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class ProductImageService
{
    /**
     * Har display size ke liye variants (1x aur 2x dono cover hote hain).
     *
     *   50  → cart slider 1x
     *   100 → cart slider 2x
     *   150 → mobile listing/home 1x
     *   200 → gallery thumb 2x, cart page 2x
     *   300 → mobile listing/home 2x, home desktop 1x
     *   400 → listing desktop ~1.5x
     *   600 → PDP desktop 1x, listing desktop 2x
     *   800 → home desktop 2x
     *  1200 → PDP desktop 2x
     *  1600 → zoom
     */
    public const SIZES = [50, 100, 150, 200, 300, 400, 600, 800, 1200, 1600];

    /** Quality — 88 par texture bacha rehta hai, size control me rehta hai */
    public const QUALITY_WEBP = 88;
    public const QUALITY_JPG  = 90;

    /** Padding ab use nahi hoti — sirf transparent PNG ke flatten ke liye rakha hai */
    public const PAD_COLOR = 'ffffff';

    protected ImageManager $manager;

    public function __construct()
    {
        // Imagick behtar hai (Lanczos filter, sharper downscale). Na ho to GD.
        $this->manager = extension_loaded('imagick')
            ? new ImageManager(new ImagickDriver)
            : new ImageManager(new GdDriver);

        @ini_set('memory_limit', '512M');
        @set_time_limit(300);
    }

    public function driverName(): string
    {
        return extension_loaded('imagick') ? 'imagick' : 'gd';
    }

    // =====================================================================
    // PATHS
    // =====================================================================

    /** Original (kabhi resize nahi hoti) */
    public function originalPath(string $filename = ''): string
    {
        return public_path('uploads/products/originals/' . $filename);
    }

    /** Responsive variant: uploads/products/r/{width}/{name}.{webp|jpg} */
    public function variantPath(int $width, string $filename = ''): string
    {
        return public_path('uploads/products/r/' . $width . '/' . $filename);
    }

    // =====================================================================
    // MAIN: upload process karo
    // =====================================================================

    /**
     * Original save karta hai + saare responsive variants banata hai.
     *
     * @param  UploadedFile $file
     * @param  string       $baseName  extension ke bina (e.g. "vase-blue-1712345-abc123")
     * @return string       original ka filename (extension ke saath)
     */
    public function process(UploadedFile $file, string $baseName): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $ext = 'jpg';
        }

        $originalName = $baseName . '.' . $ext;

        // ---- 1. ORIGINAL ko jaisa hai waisa hi rakho (koi resize nahi) ----
        File::ensureDirectoryExists($this->originalPath());
        $file->move($this->originalPath(), $originalName);
        $sourcePath = $this->originalPath($originalName);

        // ---- 2. Saare variants banao ----
        $this->generateVariants($sourcePath, $baseName);

        return $originalName;
    }

    /**
     * Sirf chhota karo — aspect ratio jaisa hai waisa hi rehta hai.
     *
     * KOI WHITE PADDING NAHI. File me image ka apna asli shape rehta hai,
     * aur square box banane ka kaam CSS ka `object-fit: cover` karta hai
     * (jo aapke design me pehle se laga hua hai).
     *
     * Isse do fayde: white patti khatam, aur file me sirf asli pixels
     * rehte hain — bekaar white area par bytes barbaad nahi hote.
     */
    protected function fitSquare($img, int $w)
    {
        // longest side ko $w tak le aao; upscale kabhi nahi
        $img->scaleDown($w, $w);

        return $img;
    }

    /**
     * Ek original file se saare width variants banata hai.
     * IMPORTANT: har size ORIGINAL se banti hai, pichhli resize se nahi —
     * warna quality har step par girti hai (purane code ki sabse badi galti).
     */
    public function generateVariants(string $sourcePath, string $baseName): void
    {
        if (! file_exists($sourcePath)) {
            return;
        }

        [$srcW, $srcH] = @getimagesize($sourcePath) ?: [0, 0];
        if (! $srcW) {
            return;
        }

        $srcMax = max($srcW, $srcH);
        $made   = 0;

        foreach (self::SIZES as $w) {
            // Original se badi size mat banao — upscale sirf blur deta hai.
            // Lekin kam se kam ek variant zaroor banao.
            if ($w > $srcMax && $made > 0) {
                continue;
            }

            $dir = $this->variantPath($w);
            File::ensureDirectoryExists($dir);

            // har baar fresh read — chaining se bachne ke liye
            $img = $this->fitSquare($this->manager->read($sourcePath), $w);

            // WebP (modern browsers) + JPG (fallback)
            $img->toWebp(self::QUALITY_WEBP)->save($dir . $baseName . '.webp');

            $img2 = $this->fitSquare($this->manager->read($sourcePath), $w);
            $img2->toJpeg(self::QUALITY_JPG)->save($dir . $baseName . '.jpg');

            unset($img, $img2);
            $made++;
        }
    }

    /**
     * Legacy paths bhi bharta hai taaki purana frontend na toote.
     * (variations/, variations/thumbs/, variations/side/, gallery/, gallery/side/, hover/)
     */
    public function writeLegacyCopies(string $sourcePath, array $targets): void
    {
        foreach ($targets as $target) {
            $dir  = $target['dir'];
            $name = $target['name'];
            $w    = $target['w'];

            File::ensureDirectoryExists($dir);

            $img = $this->fitSquare($this->manager->read($sourcePath), $w);
            $img->toJpeg(self::QUALITY_JPG)->save($dir . $name);
            unset($img);
        }
    }

    // =====================================================================
    // DELETE
    // =====================================================================

    /** Ek image ke saare variants + original delete karo */
    public function deleteAll(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $base = pathinfo($filename, PATHINFO_FILENAME);

        // original
        foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $e) {
            $p = $this->originalPath($base . '.' . $e);
            if (file_exists($p)) @unlink($p);
        }

        // variants
        foreach (self::SIZES as $w) {
            foreach (['webp', 'jpg'] as $e) {
                $p = $this->variantPath($w, $base . '.' . $e);
                if (file_exists($p)) @unlink($p);
            }
        }
    }
}