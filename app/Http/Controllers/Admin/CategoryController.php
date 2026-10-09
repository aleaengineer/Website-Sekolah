<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories-index', [
            'categories' => Category::withCount('news')->orderBy('name')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => str($validated['name'])->slug()->toString(),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        $category->loadCount('news');

        return view('admin.categories-index', [
            'categories' => Category::withCount('news')->orderBy('name')->paginate(15),
            'editing' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$category->id],
        ]);

        $slug = str($validated['name'])->slug()->toString();
        $counter = 2;

        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = str($validated['name'])->slug()->toString()."-{$counter}";
            $counter++;
        }

        $category->update(['name' => $validated['name'], 'slug' => $slug]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus. Berita di dalamnya menjadi tanpa kategori.');
    }
}
