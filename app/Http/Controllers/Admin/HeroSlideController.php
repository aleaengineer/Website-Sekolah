<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.heroes-index', [
            'slides' => HeroSlide::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.heroes-form');
    }

    public function store(StoreHeroSlideRequest $request): RedirectResponse
    {
        $slide = HeroSlide::create($this->payload($request, new HeroSlide));

        return redirect()
            ->route('admin.heroes.edit', $slide)
            ->with('success', 'Slide hero berhasil disimpan.');
    }

    public function edit(HeroSlide $hero): View
    {
        return view('admin.heroes-form', ['slide' => $hero]);
    }

    public function update(StoreHeroSlideRequest $request, HeroSlide $hero): RedirectResponse
    {
        $hero->update($this->payload($request, $hero));

        return redirect()
            ->route('admin.heroes.edit', $hero)
            ->with('success', 'Slide hero berhasil diperbarui.');
    }

    public function destroy(HeroSlide $hero): RedirectResponse
    {
        if ($hero->image) {
            Storage::disk('public')->delete($hero->image);
        }

        $hero->delete();

        return redirect()
            ->route('admin.heroes.index')
            ->with('success', 'Slide hero berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreHeroSlideRequest $request, HeroSlide $slide): array
    {
        $validated = $request->safe()->except(['image', 'is_active']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }

            $validated['image'] = $request->file('image')->store('heroes', 'public');
        }

        return $validated;
    }
}
