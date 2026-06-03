<?php

namespace App\Http\Controllers;

use App\Services\BlogPostStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function __construct(private BlogPostStore $blogPostStore) {}

    public function index(): View
    {
        return view('blog.index', [
            'posts' => $this->blogPostStore->all(),
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
                $posts = $this->blogPostStore->all();
                $posts[] = $post;

                $this->blogPostStore->save($posts);
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

    private function makeId(): string
    {
        return bin2hex(random_bytes(8));
    }
}
