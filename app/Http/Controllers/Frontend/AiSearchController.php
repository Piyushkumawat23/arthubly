<?php
// FILE: app/Http/Controllers/Frontend/AiSearchController.php
// UPDATED: har query stage log + response me debug block (sirf APP_DEBUG=true par)

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Services\AiSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AiSearchController extends Controller
{
    public function __construct(private AiSearchService $ai) {}

    private function log()
    {
        return Log::channel(config('logging.channels.aisearch') ? 'aisearch' : 'stack');
    }

    /**
     * POST /ai-search   { q: "blue pottery under 5000", fresh?: 1 }
     */
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:250']);

        $q     = trim($request->input('q'));
        $fresh = (bool) $request->boolean('fresh') && config('app.debug');
        $t0    = microtime(true);

        $this->log()->info('════════ REQUEST', [
            'q'     => $q,
            'ip'    => $request->ip(),
            'fresh' => $fresh,
        ]);

        [$categories, $colors, $sizes] = $this->vocab();

        // ---- Vocab khaali hai to Gemini kabhi accurate nahi hoga ----
        if (empty($categories)) {
            $this->log()->warning('⚠️ VOCAB KHAALI — Category::where(status,active) se 0 rows aaye. '
                . 'Column ka naam/value check karein (status = "active"?).');
        }

        $filters = $this->ai->parse($q, $categories, $colors, $sizes, $fresh);

        // ---- Stage 1: poore filters ----
        $products = $this->runQuery($filters);
        $stage    = 'strict';
        $this->log()->info('SQL stage 1 (strict)', [
            'filters' => $this->publicFilters($filters),
            'results' => $products->count(),
        ]);

        // ---- Stage 2: price/color/size hata do ----
        if ($products->isEmpty()) {
            $loose = array_merge($filters, [
                'colors' => [], 'sizes' => [], 'min_price' => null, 'max_price' => null,
            ]);
            $products = $this->runQuery($loose);
            $stage    = $products->isEmpty() ? $stage : 'relaxed';
            $this->log()->info('SQL stage 2 (relaxed — sirf keywords+category)', [
                'results' => $products->count(),
            ]);
        }

        // ---- Stage 3: category ke latest ----
        if ($products->isEmpty() && $filters['category']) {
            $products = Product::with(['variations', 'category'])
                ->where('status', 'active')
                ->whereHas('category', fn($c) => $c->where('slug', $filters['category']))
                ->latest()->take(8)->get();
            $stage = $products->isEmpty() ? $stage : 'category_fallback';
            $this->log()->info('SQL stage 3 (category ke latest)', ['results' => $products->count()]);
        }

        $relaxed = in_array($stage, ['relaxed', 'category_fallback'], true);

        $payload = [
            'reply' => $products->isEmpty()
                ? 'No products matched this description. Try something different?'
                : ($relaxed ? $filters['reply'] . ' (similar match)' : $filters['reply']),
            'filters'    => $this->publicFilters($filters),
            'used_ai'    => $filters['ok'],   // ⬅️ TRUE = Gemini ne parse kiya, FALSE = plain keyword fallback
            'count'      => $products->count(),
            'products'   => $products->map(fn($p) => $this->card($p))->values(),
            'search_url' => route('product.search', ['q' => $q]),
        ];

        // Local/dev par extra info — production me APP_DEBUG=false rakhein
        if (config('app.debug')) {
            $payload['debug'] = [
                'used_ai'      => $filters['ok'],
                'source'       => $filters['ok'] ? 'GEMINI' : 'FALLBACK (keyword LIKE)',
                'stage'        => $stage,
                'keywords'     => $filters['keywords'],
                'vocab_counts' => [
                    'categories' => count($categories),
                    'colors'     => count($colors),
                    'sizes'      => count($sizes),
                ],
                'time_ms' => round((microtime(true) - $t0) * 1000),
            ];
        }

        $this->log()->info('════════ RESPONSE', [
            'source'  => $filters['ok'] ? '🤖 GEMINI' : '🔤 FALLBACK (keyword)',
            'stage'   => $stage,
            'count'   => $products->count(),
            'time_ms' => round((microtime(true) - $t0) * 1000),
        ]);

        return response()->json($payload);
    }

    // =================================================================
    // QUERY BUILDER
    // =================================================================

    private function runQuery(array $f)
    {
        $query = Product::with(['variations', 'category'])->where('status', 'active');

        if ($f['category']) {
            $query->whereHas('category', fn($c) => $c->where('slug', $f['category']));
        }

        if (! empty($f['keywords'])) {
            $query->where(function ($q) use ($f) {
                foreach ($f['keywords'] as $kw) {
                    $q->orWhere('name', 'LIKE', "%{$kw}%")
                      ->orWhere('description', 'LIKE', "%{$kw}%")
                      ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$kw}%"))
                      ->orWhereHas('variations', fn($v) => $v->where('color', 'LIKE', "%{$kw}%"));
                }
            });
        }

        if (! empty($f['colors'])) {
            $query->where(fn($q) => $q->whereIn('color', $f['colors'])
                ->orWhereHas('variations', fn($v) => $v->whereIn('color', $f['colors'])));
        }

        if (! empty($f['sizes'])) {
            $query->where(fn($q) => $q->whereIn('size', $f['sizes'])
                ->orWhereHas('variations', fn($v) => $v->whereIn('size', $f['sizes'])));
        }

        if ($f['min_price'] !== null) $query->where('price', '>=', $f['min_price']);
        if ($f['max_price'] !== null) $query->where('price', '<=', $f['max_price']);

        match ($f['sort']) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        // Exact SQL dekhna ho to ye uncomment karein:
        // $this->log()->debug('SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query->take(12)->get();
    }

    // =================================================================
    // HELPERS
    // =================================================================

    private function vocab(): array
    {
        return Cache::remember('ai_search_vocab', now()->addHour(), function () {
            return [
                Category::where('status', 'active')->get(['name', 'slug'])->map(fn($c) => [
                    'name' => $c->name, 'slug' => $c->slug,
                ])->all(),
                Color::where('status', 'active')->pluck('name')->filter()->values()->all(),
                Size::where('status', 'active')->pluck('name')->filter()->values()->all(),
            ];
        });
    }

    private function publicFilters(array $f): array
    {
        return array_filter([
            'category'  => $f['category'],
            'colors'    => $f['colors'],
            'sizes'     => $f['sizes'],
            'min_price' => $f['min_price'],
            'max_price' => $f['max_price'],
            'sort'      => $f['sort'],
        ], fn($v) => ! empty($v) || $v === 0);
    }

    private function card(Product $p): array
    {
        $variation = $p->variations->firstWhere('image', '!=', null);

        $price = (float) ($p->sale_price ?: $p->price);
        if ($variation && $variation->price > 0) {
            $price = (float) $variation->price;
        }

        return [
            'id'        => $p->id,
            'name'      => $p->name,
            'slug'      => $p->slug,
            'category'  => optional($p->category)->name ?? 'Handmade',
            'price'     => $price,
            'price_fmt' => '₹' . number_format($price, 0),
            'image'     => $this->imageUrl($p, $variation),
            'url'       => url('product/' . $p->slug),
        ];
    }

    private function imageUrl(Product $p, $variation): string
    {
        $fallback = asset('public/uploads/products/no-image.jpg');

        if ($variation && $variation->image) {
            return asset('public/uploads/products/variations/' . $variation->image);
        }

        $img = $p->thumbnail_image;
        if (empty($img)) return $fallback;

        if (str_starts_with($img, 'http') || str_contains($img, '/')) {
            return asset($img);
        }

        return asset('public/uploads/products/' . $img);
    }
}