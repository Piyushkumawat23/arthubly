<?php
// FILE: app/Console/Commands/TestAiSearch.php
//
// Usage:
//   php artisan ai:test "blue pottery under 5000"
//   php artisan ai:test "neeli matki chahiye" --fresh
//
// Ye seedha bata dega ki Gemini connect ho raha hai ya nahi.

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Services\AiSearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestAiSearch extends Command
{
    protected $signature = 'ai:test {query : Jo search karna hai} {--fresh : Cache ignore karke fresh call}';

    protected $description = 'Gemini AI search ko terminal se test karo';

    public function handle(AiSearchService $ai): int
    {
        $this->newLine();
        $this->line('<fg=cyan>═══ 1. CONFIG CHECK ═══</>');

        $key   = config('services.gemini.key');
        $model = config('services.gemini.model');

        if (empty($key)) {
            $this->error('❌ services.gemini.key KHAALI hai.');
            $this->line('   → .env me GEMINI_API_KEY=... daalein');
            $this->line('   → config/services.php me gemini block hai? (aapka hai ✓)');
            $this->line('   → php artisan config:clear');
            return self::FAILURE;
        }

        $this->info('✅ API key: ' . substr($key, 0, 6) . '...' . substr($key, -4));
        $this->info('✅ Model:   ' . $model);

        // ---- 2. Network reachable? ----
        $this->newLine();
        $this->line('<fg=cyan>═══ 2. NETWORK CHECK ═══</>');

        try {
            $ping = Http::timeout(10)
                ->withHeaders(['x-goog-api-key' => $key])
                ->get('https://generativelanguage.googleapis.com/v1beta/models');

            if ($ping->successful()) {
                $names = collect($ping->json('models') ?? [])
                    ->pluck('name')
                    ->map(fn($n) => str_replace('models/', '', $n));

                $this->info('✅ Google tak pahunch gaye. ' . $names->count() . ' models available.');

                if (! $names->contains($model)) {
                    $this->warn("⚠️  '{$model}' list me nahi mila. Available flash models:");
                    $names->filter(fn($n) => str_contains($n, 'flash'))
                        ->take(8)
                        ->each(fn($n) => $this->line('     • ' . $n));
                }
            } else {
                $this->error('❌ HTTP ' . $ping->status());
                $this->line('   ' . mb_substr($ping->body(), 0, 300));
                if ($ping->status() === 403) {
                    $this->line('   → Key invalid hai, ya Generative Language API enable nahi hai.');
                }
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('❌ Network exception: ' . $e->getMessage());
            $this->line('   → Server se outbound HTTPS block hai (shared hosting par aam baat).');
            $this->line('   → Hosting support se generativelanguage.googleapis.com whitelist karwayein.');
            return self::FAILURE;
        }

        // ---- 3. Vocab ----
        $this->newLine();
        $this->line('<fg=cyan>═══ 3. DB VOCAB ═══</>');

        $categories = Category::where('status', 'active')->get(['name', 'slug'])
            ->map(fn($c) => ['name' => $c->name, 'slug' => $c->slug])->all();
        $colors = Color::where('status', 'active')->pluck('name')->filter()->values()->all();
        $sizes  = Size::where('status', 'active')->pluck('name')->filter()->values()->all();

        $this->line('Categories: ' . count($categories) . '  |  Colors: ' . count($colors) . '  |  Sizes: ' . count($sizes));

        if (empty($categories)) {
            $this->warn('⚠️  0 categories! Gemini category detect nahi kar payega.');
            $this->line('   → Category table me status column ki value check karein (active / 1 / published?)');
        } else {
            $this->line('   ' . collect($categories)->pluck('slug')->take(10)->implode(', '));
        }

        // ---- 4. Actual parse ----
        $this->newLine();
        $this->line('<fg=cyan>═══ 4. PARSE ═══</>');

        $query = $this->argument('query');
        $this->line('Query: "' . $query . '"');

        $started = microtime(true);
        $result  = $ai->parse($query, $categories, $colors, $sizes, (bool) $this->option('fresh'));
        $ms      = round((microtime(true) - $started) * 1000);

        $this->newLine();

        if ($result['ok']) {
            $this->info("✅ GEMINI NE PARSE KIYA ({$ms}ms)");
        } else {
            $this->error("❌ FALLBACK CHALA — Gemini use NAHI hua ({$ms}ms)");
            $this->line('   → storage/logs/ai-search.log me exact wajah likhi hai.');
        }

        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['reply',     $result['reply']],
                ['keywords',  implode(', ', $result['keywords'])],
                ['category',  $result['category'] ?? '—'],
                ['colors',    implode(', ', $result['colors']) ?: '—'],
                ['sizes',     implode(', ', $result['sizes']) ?: '—'],
                ['min_price', $result['min_price'] ?? '—'],
                ['max_price', $result['max_price'] ?? '—'],
                ['sort',      $result['sort'] ?? '—'],
            ]
        );

        $this->line('<fg=gray>Poore logs: tail -f storage/logs/ai-search.log</>');
        $this->newLine();

        return $result['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
