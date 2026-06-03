<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class BlogPostStore
{
    private const STORAGE_FILE = 'blog-posts.json';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        if (! Storage::disk('local')->exists(self::STORAGE_FILE)) {
            return [];
        }

        $raw = Storage::disk('local')->get(self::STORAGE_FILE);
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function latestSummaries(int $limit = 3): array
    {
        return array_slice(array_reverse($this->all()), 0, $limit);
    }

    /**
     * @param  array<int, array<string, mixed>>  $posts
     */
    public function save(array $posts): void
    {
        Storage::disk('local')->put(self::STORAGE_FILE, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
