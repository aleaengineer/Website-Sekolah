<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements-index', [
            'announcements' => Announcement::recent()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements-form');
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $announcement = Announcement::create($this->payload($request));

        return redirect()
            ->route('admin.announcements.edit', $announcement)
            ->with('success', 'Pengumuman berhasil disimpan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements-form', ['announcement' => $announcement]);
    }

    public function update(StoreAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($this->payload($request));

        return redirect()
            ->route('admin.announcements.edit', $announcement)
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(StoreAnnouncementRequest $request): array
    {
        $validated = $request->safe()->except(['is_published', 'is_pinned']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_pinned'] = $request->boolean('is_pinned');

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        return $validated;
    }
}
