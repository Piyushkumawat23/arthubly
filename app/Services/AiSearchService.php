<?php
// FILE: app/Services/AiSearchService.php
// v4 FIX (Gemini 3.x compatible):
//   ❌ 'temperature'    hata diya — 3.x me deprecated, 400 deta hai
//   ❌ 'thinkingConfig' hata diya — thinkingBudget 2.5-era ka param hai
//   ✅ 3-step ladder: full → no schema → bare minimum
//   ✅ maxOutputTokens 2048 (3.x by default sochta hai, wo tokens isi budget se jaate hain)

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSearchService
{
    /** Payload levels */
    private const LEVEL_FULL   = 1;  // systemInstruction + responseSchema + JSON mime
    private const LEVEL_NO_SCHEMA = 2;  // systemInstruction + JSON mime
    private const LEVEL_BARE   = 3;  // sirf contents (system prompt user text me merge)

    private function log()
    {
        return Log::channel(config('logging.channels.aisearch') ? 'aisearch' : 'stack');
    }

    public function parse(string $query, array $categories, array $colors, array $sizes, bool $fresh = false): array
    {
        $query = trim(mb_substr($query, 0, 250));

        $this->log()->info('┌── AI SEARCH START', [
            'query' => $query,
            'model' => config('services.gemini.model'),
            'vocab' => ['cat' => count($categories), 'col' => count($colors), 'size' => count($sizes)],
        ]);

        if ($query === '') {
            return $this->blank('Kuch likhiye to sahi 🙂');
        }

        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            $this->log()->error('└── NO API KEY ❌');
            return $this->blank(null, false, $query);
        }

        $cacheKey = 'ai_search:' . md5(mb_strtolower($query) . '|' . count($categories));

        if (! $fresh && Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            $this->log()->info('└── CACHE HIT — Gemini call nahi hui', ['filters' => $cached]);
            return $cached;
        }

        // Ladder: har level pichhle se simple. Jo chal jaaye, wahi.
        foreach ([self::LEVEL_FULL, self::LEVEL_NO_SCHEMA, self::LEVEL_BARE] as $level) {
            $result = $this->callGemini($query, $categories, $colors, $sizes, $apiKey, $level);

            if ($result['ok']) {
                Cache::put($cacheKey, $result, now()->addHours(6));
                return $result;
            }

            if ($level !== self::LEVEL_BARE) {
                $this->log()->warning("│   Level {$level} fail — agla level try kar rahe hain");
            }
        }

        $this->log()->error('└── SAB LEVELS FAIL ❌ — fallback keyword search chalegi');
        return $this->blank(null, false, $query);
    }

    // =================================================================

    private function callGemini(string $q, array $cats, array $cols, array $sizes, string $key, int $level): array
    {
        $url     = $this->endpoint();
        $started = microtime(true);
        $body    = $this->payload($q, $cats, $cols, $sizes, $level);

        try {
            $response = Http::timeout(25)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, $body);

            $ms = round((microtime(true) - $started) * 1000);

            if (! $response->successful()) {
                $this->log()->error("│   L{$level} HTTP FAIL ❌", [
                    'status'       => $response->status(),
                    'time_ms'      => $ms,
                    'body'         => mb_substr($response->body(), 0, 900),
                    'matlab'       => $this->hintForStatus($response->status()),
                    // 400 debug karne ke liye — humne exactly kya bheja
                    'sent_keys'    => array_keys($body),
                    'sent_genconf' => $body['generationConfig'] ?? null,
                ]);
                return $this->blank(null, false, $q);
            }

            $json   = $response->json();
            $finish = data_get($json, 'candidates.0.finishReason');

            $parts = data_get($json, 'candidates.0.content.parts', []);
            $text  = collect($parts)->pluck('text')->filter()->implode('');

            $this->log()->info("│   L{$level} responded", [
                'time_ms'       => $ms,
                'finish_reason' => $finish,
                'tokens'        => data_get($json, 'usageMetadata'),
                'text_len'      => mb_strlen($text),
                'raw_text'      => mb_substr($text, 0, 600),
            ]);

            if ($text === '') {
                $this->log()->error("│   L{$level} EMPTY TEXT ❌", [
                    'finish_reason' => $finish,
                    'matlab'        => $this->hintForFinish($finish),
                    'block_reason'  => data_get($json, 'promptFeedback.blockReason'),
                    'full'          => mb_substr(json_encode($json), 0, 900),
                ]);
                return $this->blank(null, false, $q);
            }

            $data = json_decode($this->stripFences($text), true);

            if (! is_array($data)) {
                $this->log()->error("│   L{$level} JSON PARSE FAIL ❌", [
                    'json_error' => json_last_error_msg(),
                    'text'       => mb_substr($text, 0, 500),
                ]);
                return $this->blank(null, false, $q);
            }

            $clean = $this->normalize($data, $cats, $cols, $sizes, $q);

            $this->log()->info("└── AI PARSE OK ✅ (level {$level})", [
                'gemini_ne_bheja' => $data,
                'humne_rakha'     => $clean,
                'drop_hua'        => $this->diff($data, $clean),
            ]);

            return $clean;

        } catch (\Throwable $e) {
            $this->log()->error("│   L{$level} EXCEPTION ❌", [
                'message' => $e->getMessage(),
                'class'   => get_class($e),
                'time_ms' => round((microtime(true) - $started) * 1000),
            ]);
            return $this->blank(null, false, $q);
        }
    }

    private function hintForStatus(int $s): string
    {
        return match ($s) {
            400      => 'Payload reject. sent_genconf dekhein — koi param is model par supported nahi hai.',
            403      => 'Key ke paas is model ka access nahi.',
            404      => 'Model name galat / deprecated — .env GEMINI_MODEL badlein.',
            429      => 'Quota khatam.',
            500, 503 => 'Google ki taraf se temporary error.',
            default  => 'Body field upar dekhein.',
        };
    }

    private function hintForFinish(?string $f): string
    {
        return match ($f) {
            'MAX_TOKENS' => 'maxOutputTokens kam pad gaya (3.x thinking bhi isi budget se leta hai). Badha dein.',
            'SAFETY'     => 'Safety filter ne block kiya.',
            'RECITATION' => 'Recitation filter.',
            default      => 'finishReason: ' . ($f ?? 'null'),
        };
    }

    private function diff(array $raw, array $clean): array
    {
        $out = [];
        if (($raw['category'] ?? null) && ! $clean['category']) {
            $out['category'] = "Gemini ne '{$raw['category']}' bheja par ye slug DB me nahi hai";
        }
        if (count($raw['colors'] ?? []) > count($clean['colors'])) {
            $out['colors'] = 'DB list se match nahi hue: ' . implode(', ', $raw['colors'] ?? []);
        }
        if (count($raw['sizes'] ?? []) > count($clean['sizes'])) {
            $out['sizes'] = 'DB list se match nahi hue: ' . implode(', ', $raw['sizes'] ?? []);
        }
        return $out;
    }

    // =================================================================

    private function endpoint(): string
    {
        $model = config('services.gemini.model', 'gemini-3.5-flash-lite');
        return "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";
    }

    private function instructions(array $categories, array $colors, array $sizes): string
    {
        $catList  = collect($categories)->map(fn($c) => "{$c['name']} ({$c['slug']})")->implode(', ') ?: 'none';
        $colList  = implode(', ', $colors) ?: 'none';
        $sizeList = implode(', ', $sizes) ?: 'none';

        return <<<TXT
You are the search-query parser for Arthubly, an Indian handmade-crafts marketplace.
Convert the shopper's natural language request into search filters. Reply ONLY with a JSON object — no markdown, no code fences, no explanation.

JSON shape:
{"keywords":["string"],"category":"slug-or-null","colors":["string"],"sizes":["string"],"min_price":number-or-null,"max_price":number-or-null,"sort":"price_low|price_high|null","reply":"string"}

Available categories (name (slug)): {$catList}
Available colors: {$colList}
Available sizes: {$sizeList}

Rules:
- "category" MUST be one of the slugs listed above, or null if nothing clearly matches.
- "colors" / "sizes" MUST be picked from the lists above (exact spelling), else empty array.
- Prices are in Indian Rupees. "under 5000" -> max_price 5000. "5k" -> 5000. "between 1000 and 3000" -> min 1000, max 3000. "budget"/"sasta" -> max_price 1500. No price mentioned -> null.
- "keywords": 1-4 short product words for a LIKE search (e.g. ["pottery","vase"]). Drop filler like "show me", "I want", "under". Translate Hindi/Hinglish to English ("neeli matki" -> ["blue","pot"]).
- "sort": "price_low" for cheap/budget first, "price_high" for premium/luxury, else null.
- "reply": ONE short friendly line (max 14 words). Match their language (Hindi/Hinglish query -> Hinglish reply).
TXT;
    }

    private function payload(string $query, array $categories, array $colors, array $sizes, int $level): array
    {
        $system = $this->instructions($categories, $colors, $sizes);

        // ---- LEVEL 3: sabse minimal — sirf contents, koi config nahi ----
        if ($level === self::LEVEL_BARE) {
            return [
                'contents' => [[
                    'role'  => 'user',
                    'parts' => [['text' => $system . "\n\nShopper's request: " . $query]],
                ]],
            ];
        }

        // NOTE: yahan 'temperature' aur 'thinkingConfig' jaan-boojh kar NAHI hain.
        // Gemini 3.x me temperature/top_p/top_k deprecated hain aur thinkingBudget
        // 2.5-era ka param hai — dono INVALID_ARGUMENT (400) dete hain.
        $gen = [
            'maxOutputTokens'  => 2048,
            'responseMimeType' => 'application/json',
        ];

        if ($level === self::LEVEL_FULL) {
            $gen['responseSchema'] = [
                'type'       => 'OBJECT',
                'properties' => [
                    'keywords'  => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
                    'category'  => ['type' => 'STRING', 'nullable' => true],
                    'colors'    => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
                    'sizes'     => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
                    'min_price' => ['type' => 'NUMBER', 'nullable' => true],
                    'max_price' => ['type' => 'NUMBER', 'nullable' => true],
                    'sort'      => ['type' => 'STRING', 'nullable' => true],
                    'reply'     => ['type' => 'STRING'],
                ],
                'required' => ['keywords', 'reply'],
            ];
        }

        return [
            'systemInstruction' => ['parts' => [['text' => $system]]],
            'contents' => [[
                'role'  => 'user',
                'parts' => [['text' => $query]],
            ]],
            'generationConfig' => $gen,
        ];
    }

    // =================================================================

    private function stripFences(string $text): string
    {
        $t = trim(preg_replace('/^```(?:json)?|```$/m', '', trim($text)));

        if (! str_starts_with($t, '{')) {
            $s = strpos($t, '{');
            $e = strrpos($t, '}');
            if ($s !== false && $e !== false && $e > $s) {
                $t = substr($t, $s, $e - $s + 1);
            }
        }

        return $t;
    }

    private function normalize(array $d, array $categories, array $colors, array $sizes, string $query): array
    {
        $slugs = array_column($categories, 'slug');

        $category = $d['category'] ?? null;
        if (! in_array($category, $slugs, true)) {
            $category = null;
        }

        $pick = function ($values, $allowed) {
            $values = is_array($values) ? $values : [];
            $map = [];
            foreach ($allowed as $a) {
                $map[mb_strtolower($a)] = $a;
            }
            $out = [];
            foreach ($values as $v) {
                $k = mb_strtolower(trim((string) $v));
                if (isset($map[$k])) $out[] = $map[$k];
            }
            return array_values(array_unique($out));
        };

        $keywords = collect($d['keywords'] ?? [])
            ->map(fn($k) => trim((string) $k))
            ->filter(fn($k) => mb_strlen($k) >= 2)
            ->take(4)->values()->all();

        if (empty($keywords)) {
            $keywords = [$query];
        }

        $min = isset($d['min_price']) && is_numeric($d['min_price']) ? (float) $d['min_price'] : null;
        $max = isset($d['max_price']) && is_numeric($d['max_price']) ? (float) $d['max_price'] : null;
        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $sort = in_array($d['sort'] ?? null, ['price_low', 'price_high', 'latest'], true) ? $d['sort'] : null;

        return [
            'ok'        => true,
            'keywords'  => $keywords,
            'category'  => $category,
            'colors'    => $pick($d['colors'] ?? [], $colors),
            'sizes'     => $pick($d['sizes'] ?? [], $sizes),
            'min_price' => $min,
            'max_price' => $max,
            'sort'      => $sort,
            'reply'     => mb_substr(trim((string) ($d['reply'] ?? '')), 0, 140) ?: 'Ye rahe kuch matching pieces:',
        ];
    }

    private function blank(?string $reply = null, bool $ok = false, string $query = ''): array
    {
        return [
            'ok'        => $ok,
            'keywords'  => $query !== '' ? [$query] : [],
            'category'  => null,
            'colors'    => [],
            'sizes'     => [],
            'min_price' => null,
            'max_price' => null,
            'sort'      => null,
            'reply'     => $reply ?: 'Ye rahe kuch matching pieces:',
        ];
    }
}