<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
        ]);

        $news = News::published()->recent()
            ->with('category')
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where(fn ($sub) => $sub
                ->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")))
            ->when($filters['kategori'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($related) => $related->where('slug', $slug)))
            ->paginate(9)
            ->withQueryString();

        return view('pages.news-index', [
            'news' => $news,
            'categories' => Category::orderBy('name')->get(),
            'activeCategory' => $filters['kategori'] ?? null,
        ]);
    }

    public function show(News $news): View
    {
        abort_unless($news->is_published && $news->published_at?->isPast(), 404);

        return view('pages.news-show', [
            'article' => $news,
            'related' => News::published()->recent()
                ->where('id', '!=', $news->id)
                ->take(3)
                ->get(),
        ]);
    }
}
