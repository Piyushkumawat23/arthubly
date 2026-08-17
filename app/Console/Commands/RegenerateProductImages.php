<?php
// FILE: app/Console/Commands/RegenerateProductImages.php
//
// Chalane ka tarika:
//   php artisan images:regenerate            → sab
//   php artisan images:regenerate --id=42    → sirf product 42
//   php artisan images:regenerate --force    → jo variants pehle se hain, unhe bhi dubara banao

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Services\ProductImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegenerateProductImages extends Command
{
    protected $signature = 'images:regenerate {--id=} {--force}';

    protected $description = 'Purani product images se responsive variants (webp+jpg) banata hai';

    public function handle(ProductImageService $svc): int
    {
        $this->info('Driver: ' . $svc->driverName());

        $query = Product::with(['images', 'variations']);
        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        }

        $products = $query->get();
        $this->info('Products: ' . $products->count());

        $done = 0;
        $skip = 0;

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {

            // ---- variations ----
            foreach ($product->variations as $var) {
                if ($this->handleOne($svc, $var->image, 'variations')) $done++; else $skip++;
            }

            // ---- gallery ----
            foreach ($product->images as $img) {
                if ($this->handleOne($svc, $img->image, 'gallery')) $done++; else $skip++;
            }

            // ---- hover ----
            if ($product->hover_image) {
                if ($this->handleOne($svc, $product->hover_image, 'hover')) $done++; else $skip++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Generated: {$done}   Skipped: {$skip}");

        return self::SUCCESS;
    }

    /**
     * Source dhoondho (pehle originals/, phir purana folder) aur variants banao.
     */
    protected function handleOne(ProductImageService $svc, ?string $filename, string $legacyFolder): bool
    {
        if (! $filename) {
            return false;
        }

        $base = pathinfo($filename, PATHINFO_FILENAME);

        // pehle se ban chuka hai?
        if (! $this->option('force')) {
            $check = public_path('uploads/products/r/600/' . $base . '.webp');
            if (file_exists($check)) {
                return false;
            }
        }

        // source: original hai to sabse achha
        $source = null;
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $e) {
            $p = public_path('uploads/products/originals/' . $base . '.' . $e);
            if (file_exists($p)) { $source = $p; break; }
        }

        // original nahi hai — purani (already resized) file se hi kaam chalao
        if (! $source) {
            $p = public_path('uploads/products/' . $legacyFolder . '/' . $filename);
            if (file_exists($p)) {
                $source = $p;

                // aage ke liye ise original ki jagah copy kar do
                File::ensureDirectoryExists(public_path('uploads/products/originals'));
                @copy($p, public_path('uploads/products/originals/' . $filename));
            }
        }

        if (! $source) {
            return false;
        }

        $svc->generateVariants($source, $base);

        return true;
    }
}
