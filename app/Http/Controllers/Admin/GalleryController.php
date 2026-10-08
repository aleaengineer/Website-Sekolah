<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.galleries-index', [
            'galleries' => Gallery::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries-form');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $gallery = Gallery::create($this->payload($request, new Gallery));

        return redirect()
            ->route('admin.galleries.edit', $gallery)
            ->with('success', 'Foto galeri berhasil disimpan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries-form', ['gallery' => $gallery]);
    }

    public function update(StoreGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $gallery->update($this->payload($request, $gallery));

        return redirect()
            ->route('admin.galleries.edit', $gallery)
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreGalleryRequest $request, Gallery $gallery): array
    {
        $validated = $request->safe()->except(['image']);

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }

            $validated['image'] = $request->file('image')->store('galleries', 'public');
        }

        return $validated;
    }
}
