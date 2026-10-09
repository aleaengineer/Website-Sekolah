<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\DatabaseBackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function __construct(private readonly DatabaseBackupService $backups) {}

    public function index(): View
    {
        return view('admin.backups-index', [
            'files' => $this->backups->files(),
        ]);
    }

    public function store(): RedirectResponse
    {
        try {
            $name = $this->backups->create();
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('admin.backups.index')
                ->withErrors(['backup' => $exception->getMessage()]);
        }

        ActivityLog::record(ActivityLog::ACTION_BACKUP, "membuat backup database {$name}");

        return redirect()
            ->route('admin.backups.index')
            ->with('success', "Backup {$name} berhasil dibuat.");
    }

    public function download(string $file): BinaryFileResponse
    {
        return response()->download($this->backups->path($file));
    }

    public function destroy(string $file): RedirectResponse
    {
        $this->backups->delete($file);

        return redirect()
            ->route('admin.backups.index')
            ->with('success', "Backup {$file} berhasil dihapus.");
    }

    public function restore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'backup' => ['required', 'file', 'mimes:sql,sqlite,db', 'max:51200'],
        ]);

        try {
            $this->backups->restore($validated['backup']->getRealPath());
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('admin.backups.index')
                ->withErrors(['backup' => $exception->getMessage()]);
        }

        ActivityLog::record(ActivityLog::ACTION_RESTORE, 'me-restore database dari berkas unggahan');

        return redirect()
            ->route('admin.backups.index')
            ->with('success', 'Restore database berhasil. Periksa kembali data situs.');
    }
}
