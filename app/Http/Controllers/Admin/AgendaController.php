<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        return view('admin.agendas-index', [
            'agendas' => Agenda::recent()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.agendas-form');
    }

    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        $agenda = Agenda::create($this->payload($request, new Agenda));

        return redirect()
            ->route('admin.agendas.edit', $agenda)
            ->with('success', 'Agenda berhasil disimpan.');
    }

    public function edit(Agenda $agenda): View
    {
        return view('admin.agendas-form', ['agenda' => $agenda]);
    }

    public function update(StoreAgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        $agenda->update($this->payload($request, $agenda));

        return redirect()
            ->route('admin.agendas.edit', $agenda)
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        if ($agenda->cover_image) {
            Storage::disk('public')->delete($agenda->cover_image);
        }

        $agenda->delete();

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreAgendaRequest $request, Agenda $agenda): array
    {
        $validated = $request->safe()->except(['cover_image', 'is_published']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['slug'] = $this->slug($request->string('title')->toString(), $agenda->id);

        if ($request->hasFile('cover_image')) {
            if ($agenda->cover_image) {
                Storage::disk('public')->delete($agenda->cover_image);
            }

            $validated['cover_image'] = $request->file('cover_image')->store('agendas', 'public');
        }

        return $validated;
    }

    private function slug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'agenda';

        $slug = $base;
        $counter = 2;

        while (Agenda::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
