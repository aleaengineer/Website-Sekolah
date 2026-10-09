<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('admin.documents-index', [
            'documents' => Document::ordered()->paginate(15),
            'categories' => Document::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('admin.documents-form', ['categories' => Document::CATEGORIES]);
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $validated = $this->payload($request, new Document);

        if (! isset($validated['file'])) {
            return redirect()
                ->route('admin.documents.create')
                ->withErrors(['file' => 'Berkas wajib diunggah untuk dokumen baru.'])
                ->withInput();
        }

        $document = Document::create($validated);

        return redirect()
            ->route('admin.documents.edit', $document)
            ->with('success', 'Dokumen berhasil disimpan.');
    }

    public function edit(Document $document): View
    {
        return view('admin.documents-form', [
            'document' => $document,
            'categories' => Document::CATEGORIES,
        ]);
    }

    public function update(StoreDocumentRequest $request, Document $document): RedirectResponse
    {
        $document->update($this->payload($request, $document));

        return redirect()
            ->route('admin.documents.edit', $document)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        if ($document->file) {
            Storage::disk('public')->delete($document->file);
        }

        $document->delete();

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreDocumentRequest $request, Document $document): array
    {
        $validated = $request->safe()->except(['file', 'is_published']);
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('file')) {
            if ($document->file) {
                Storage::disk('public')->delete($document->file);
            }

            $validated['file'] = $request->file('file')->store('documents', 'public');
        }

        return $validated;
    }
}
