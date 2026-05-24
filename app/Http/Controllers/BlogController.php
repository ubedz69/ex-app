<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    private const STORAGE_FILE = 'blog-posts.json';

    public function index(): View
    {
        $posts = $this->loadPosts();

        return view('blog.index', [
            'posts' => $posts,
        ]);
    }

    public function create(): View
    {
        return view('blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        $post = [
            'id' => $this->makeId(),
            'title' => $data['title'],
            'summary' => $data['summary'],
            'content' => $data['content'],
            'created_at' => now()->toDateTimeString(),
        ];

        try {
            Cache::lock('blog-posts-write', 10)->block(5, function () use ($post): void {
                $posts = $this->loadPosts();
                $posts[] = $post;

                $this->savePosts($posts);
            });
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['blog' => 'Gagal menyimpan post. Silakan coba lagi.']);
        }

        return redirect()->to('/blog')->with('status', 'Blog berhasil diposting.');
    }

    public function latestSummaries(int $limit = 3): array
    {
        $posts = $this->loadPosts();

        $posts = array_reverse($posts);

        return array_slice($posts, 0, $limit);
    }

    private function loadPosts(): array
    {
        if (! Storage::disk('local')->exists(self::STORAGE_FILE)) {
            return [];
        }

        $raw = Storage::disk('local')->get(self::STORAGE_FILE);
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function savePosts(array $posts): void
    {
        Storage::disk('local')->put(self::STORAGE_FILE, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function makeId(): string
    {
        return bin2hex(random_bytes(8));
    }
}
