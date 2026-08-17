<?php
// FILE: app/Helpers/Img.php
//
// Blade me use: {!! \App\Helpers\Img::tag($var->image, 'product name', 'listing', 'pc-img main') !!}

namespace App\Helpers;

class Img
{
    /**
     * Har jagah ki display width (desktop / mobile) —
     * aapke diye gaye numbers ke hisaab se.
     */
    public const SPOTS = [
        // spot           => [desktop, mobile, tablet(optional)]
        'pdp'             => ['d' => 602, 'm' => 390, 't' => 500],
        'listing'         => ['d' => 272, 'm' => 146, 't' => 340],
        'home'            => ['d' => 317, 'm' => 140, 't' => 300],
        'gallery_thumb'   => ['d' => 82,  'm' => 66,  't' => 82],
        'cart_page'       => ['d' => 94,  'm' => 70,  't' => 94],
        'cart_slider'     => ['d' => 50,  'm' => 50,  't' => 50],
        'zoom'            => ['d' => 1600,'m' => 1600,'t' => 1600],
    ];

    /** Service ki generated widths */
    public const SIZES = [50, 100, 150, 200, 300, 400, 600, 800, 1200, 1600];

    /** Variant ka URL (agar file maujood hai) */
    public static function url(?string $filename, int $width, string $ext = 'webp'): ?string
    {
        if (! $filename) return null;

        $base = pathinfo($filename, PATHINFO_FILENAME);
        $rel  = 'uploads/products/r/' . $width . '/' . $base . '.' . $ext;

        return file_exists(public_path($rel)) ? asset('public/' . $rel) : null;
    }

    /** Original file ka URL (zoom ke liye) */
    public static function original(?string $filename): ?string
    {
        if (! $filename) return null;

        $rel = 'uploads/products/originals/' . $filename;

        return file_exists(public_path($rel)) ? asset('public/' . $rel) : null;
    }

    /** Purane (legacy) path — jab tak regenerate na chale, fallback ke liye */
    public static function legacy(?string $filename, string $folder = 'variations'): string
    {
        if (! $filename) {
            return asset('public/uploads/products/no-image.jpg');
        }

        return asset('public/uploads/products/' . trim($folder, '/') . '/' . $filename);
    }

    /**
     * srcset string banata hai — sirf wahi widths jo actually exist karti hain.
     */
    public static function srcset(?string $filename, string $ext = 'webp', int $maxWidth = 1600): string
    {
        if (! $filename) return '';

        $base = pathinfo($filename, PATHINFO_FILENAME);
        $out  = [];

        foreach (self::SIZES as $w) {
            if ($w > $maxWidth) continue;

            $rel = 'uploads/products/r/' . $w . '/' . $base . '.' . $ext;
            if (file_exists(public_path($rel))) {
                $out[] = asset('public/' . $rel) . ' ' . $w . 'w';
            }
        }

        return implode(', ', $out);
    }

    /**
     * sizes attribute — browser ko batata hai ki kis viewport par kitni width chahiye.
     */
    public static function sizesAttr(string $spot): string
    {
        $s = self::SPOTS[$spot] ?? self::SPOTS['listing'];

        return '(max-width: 767px) ' . $s['m'] . 'px, '
             . '(max-width: 1080px) ' . $s['t'] . 'px, '
             . $s['d'] . 'px';
    }

    /**
     * Poora <picture> tag. Design bilkul nahi badalta —
     * class, alt, style sab aap hi pass karte hain.
     *
     * @param string $legacyFolder purani file kahan thi (fallback ke liye)
     */
    public static function tag(
        ?string $filename,
        string $alt = '',
        string $spot = 'listing',
        string $class = '',
        string $legacyFolder = 'variations',
        array $attrs = [],
        bool $usePicture = true      // PDP zoom ke liye false bhejein
    ): string {
        $spotCfg  = self::SPOTS[$spot] ?? self::SPOTS['listing'];
        $maxW     = max($spotCfg['d'], $spotCfg['m'], $spotCfg['t']) * 2;

        $webp     = self::srcset($filename, 'webp', $maxW);
        $jpg      = self::srcset($filename, 'jpg', $maxW);
        $sizes    = self::sizesAttr($spot);
        $fallback = self::url($filename, self::nearest($spotCfg['d']), 'jpg')
                    ?? self::legacy($filename, $legacyFolder);

        $extra = '';
        foreach ($attrs as $k => $v) {
            $extra .= ' ' . $k . '="' . e($v) . '"';
        }

        $imgTag = '<img src="' . $fallback . '"'
            . ($jpg ? ' srcset="' . $jpg . '" sizes="' . $sizes . '"' : '')
            . ' alt="' . e($alt) . '"'
            . ($class ? ' class="' . e($class) . '"' : '')
            . ' loading="lazy" decoding="async"'
            . ' onerror="this.onerror=null;this.src=\'' . asset('public/uploads/products/no-image.jpg') . '\'"'
            . $extra . '>';

        // <picture> wrapper kuch zoom/slider plugins ko todta hai
        // (wo .parent() se container dhoondhte hain). Aise jagah par
        // $usePicture = false bhejein — srcset phir bhi kaam karega.
        if (! $webp || ! $usePicture) {
            return $imgTag;
        }

        return '<picture>'
            . '<source type="image/webp" srcset="' . $webp . '" sizes="' . $sizes . '">'
            . $imgTag
            . '</picture>';
    }

    /**
     * Sirf <img> — koi <picture> wrapper nahi.
     * Zoom lens, sliders, ya kisi bhi JS plugin wali image ke liye use karein.
     */
    public static function img(
        ?string $filename,
        string $alt = '',
        string $spot = 'listing',
        string $class = '',
        string $legacyFolder = 'variations',
        array $attrs = []
    ): string {
        return self::tag($filename, $alt, $spot, $class, $legacyFolder, $attrs, false);
    }

    /** Di gayi width ke sabse kareeb wali generated size */
    public static function nearest(int $want): int
    {
        $best = self::SIZES[0];
        foreach (self::SIZES as $w) {
            if ($w >= $want) return $w;
            $best = $w;
        }
        return $best;
    }
}