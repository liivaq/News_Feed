<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;

class Cache
{
    public static function has(string $key): bool
    {
        $cacheFile = dirname(__FILE__, 2).'/cache/'. $key;
        if (!file_exists($cacheFile)) {
            return false;
        }
        $content = json_decode(file_get_contents($cacheFile));
        return Carbon::parse($content->expires_at)->gt(Carbon::now());
    }

    public static function save(string $key, string $content, int $ttl = 120): void
    {
        $cacheFile = dirname(__FILE__, 2).'/cache/'. $key;
        file_put_contents($cacheFile, json_encode([
            'expires_at' => Carbon::now()->addSeconds($ttl),
            'content' => $content
        ]));
    }

    public static function get(string $key): string
    {
        $cacheFile = dirname(__FILE__, 2).'/cache/'. $key;
        $content = json_decode(file_get_contents($cacheFile));
        return $content->content;
    }
}