<?php

namespace App\Http\Controllers;

use App\Services\BlogPostStore;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(BlogPostStore $blogPostStore): View
    {
        return view('home', [
            'posts' => $blogPostStore->latestSummaries(3),
        ]);
    }
}
