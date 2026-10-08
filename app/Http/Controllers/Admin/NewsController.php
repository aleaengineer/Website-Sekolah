<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('admin.news-index', [
            'news' => News::with('category')->recent()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.news-form', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $data = $this->payload($request, new News);

        $article = News::create($data);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(News $news): View
    {
        return view('admin.news-form', [
            'article' => $news,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(StoreNewsRequest $request, News $news): RedirectResponse
    {
        $news->update($this->payload($request, $news));

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        if ($news->cover_image) {
            Storage::disk('public')->delete($news->cover_image);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreNewsRequest $request, News $news): array
    {
        $validated = $request->safe()->except(['cover_image', 'is_published', 'slug']);

        $slug = $request->input('slug') ?: Str::slug($request->input('title'));
        $validated['slug'] = $this->uniqueSlug($slug, $news->id);

        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('cover_image')) {
            if ($news->cover_image) {
                Storage::disk('public')->delete($news->cover_image);
            }

            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        return $validated;
    }

    private function uniqueSlug(string $slug, ?int $ignoreId): string
    {
        $candidate = $slug;
        $counter = 2;

        while (News::where('slug', $candidate)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $candidate = "{$slug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }
}
