<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExtracurricularRequest;
use App\Models\Extracurricular;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    public function index(): View
    {
        return view('admin.extracurriculars-index', [
            'extracurriculars' => Extracurricular::ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.extracurriculars-form');
    }

    public function store(StoreExtracurricularRequest $request): RedirectResponse
    {
        $item = Extracurricular::create($this->payload($request, new Extracurricular));

        return redirect()
            ->route('admin.extracurriculars.edit', $item)
            ->with('success', 'Ekstrakurikuler berhasil disimpan.');
    }

    public function edit(Extracurricular $extracurricular): View
    {
        return view('admin.extracurriculars-form', ['item' => $extracurricular]);
    }

    public function update(StoreExtracurricularRequest $request, Extracurricular $extracurricular): RedirectResponse
    {
        $extracurricular->update($this->payload($request, $extracurricular));

        return redirect()
            ->route('admin.extracurriculars.edit', $extracurricular)
            ->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        $extracurricular->delete();

        return redirect()
            ->route('admin.extracurriculars.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreExtracurricularRequest $request, Extracurricular $item): array
    {
        $validated = $request->safe()->except(['slug', 'is_active']);

        $slug = $request->input('slug') ?: Str::slug($request->input('name'));
        $validated['slug'] = $this->uniqueSlug($slug, $item->id);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function uniqueSlug(string $slug, ?int $ignoreId): string
    {
        $candidate = $slug;
        $counter = 2;

        while (Extracurricular::where('slug', $candidate)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $candidate = "{$slug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }
}
