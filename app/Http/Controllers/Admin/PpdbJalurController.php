<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePpdbJalurRequest;
use App\Models\PpdbJalur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PpdbJalurController extends Controller
{
    public function index(): View
    {
        return view('admin.jalurs-index', [
            'jalurs' => PpdbJalur::ordered()->withCount('registrations')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.jalurs-form');
    }

    public function store(StorePpdbJalurRequest $request): RedirectResponse
    {
        $jalur = PpdbJalur::create([...$this->payload($request), 'slug' => $this->slug($request->string('name')->toString())]);

        return redirect()
            ->route('admin.jalurs.edit', $jalur)
            ->with('success', 'Jalur PPDB berhasil disimpan.');
    }

    public function edit(PpdbJalur $jalur): View
    {
        $jalur->loadCount('registrations');

        return view('admin.jalurs-form', ['jalur' => $jalur]);
    }

    public function update(StorePpdbJalurRequest $request, PpdbJalur $jalur): RedirectResponse
    {
        $jalur->update($this->payload($request));

        return redirect()
            ->route('admin.jalurs.edit', $jalur)
            ->with('success', 'Jalur PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbJalur $jalur): RedirectResponse
    {
        if ($jalur->registrations()->exists()) {
            return redirect()
                ->route('admin.jalurs.index')
                ->withErrors(['jalur' => "Jalur {$jalur->name} sudah dipakai pendaftar dan tidak bisa dihapus. Nonaktifkan saja bila tidak dipakai lagi."]);
        }

        $jalur->delete();

        return redirect()
            ->route('admin.jalurs.index')
            ->with('success', 'Jalur PPDB berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StorePpdbJalurRequest $request): array
    {
        $validated = $request->safe()->except(['is_active']);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function slug(string $name): string
    {
        $base = Str::slug($name) ?: 'jalur';

        $slug = $base;
        $counter = 2;

        while (PpdbJalur::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
