<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePpdbWaveRequest;
use App\Models\PpdbWave;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PpdbWaveController extends Controller
{
    public function index(): View
    {
        return view('admin.waves-index', [
            'waves' => PpdbWave::withCount('registrations')->orderBy('start_date')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.waves-form');
    }

    public function store(StorePpdbWaveRequest $request): RedirectResponse
    {
        $wave = PpdbWave::create($this->payload($request));

        return redirect()
            ->route('admin.waves.edit', $wave)
            ->with('success', 'Gelombang PPDB berhasil disimpan.');
    }

    public function edit(PpdbWave $wave): View
    {
        $wave->loadCount('registrations');

        return view('admin.waves-form', ['wave' => $wave]);
    }

    public function update(StorePpdbWaveRequest $request, PpdbWave $wave): RedirectResponse
    {
        $wave->update($this->payload($request));

        return redirect()
            ->route('admin.waves.edit', $wave)
            ->with('success', 'Gelombang PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbWave $wave): RedirectResponse
    {
        $wave->delete();

        return redirect()
            ->route('admin.waves.index')
            ->with('success', 'Gelombang PPDB berhasil dihapus. Pendaftar lama tetap tersimpan tanpa gelombang.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StorePpdbWaveRequest $request): array
    {
        $validated = $request->safe()->except(['is_active']);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
