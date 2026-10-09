<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')->recent()
            ->when($request->input('action'), fn ($query, $action) => $query->where('action', $action))
            ->when($request->input('user_id'), fn ($query, $userId) => $query->where('user_id', $userId))
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs-index', [
            'logs' => $logs,
            'actions' => $this->actions(),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function actions(): array
    {
        return [
            ActivityLog::ACTION_CREATE => 'Tambah',
            ActivityLog::ACTION_UPDATE => 'Ubah',
            ActivityLog::ACTION_DELETE => 'Hapus',
            ActivityLog::ACTION_LOGIN => 'Masuk',
            ActivityLog::ACTION_LOGOUT => 'Keluar',
            ActivityLog::ACTION_EXPORT => 'Ekspor',
            ActivityLog::ACTION_IMPORT => 'Impor',
            ActivityLog::ACTION_VERIFY => 'Verifikasi',
            ActivityLog::ACTION_BACKUP => 'Backup',
            ActivityLog::ACTION_RESTORE => 'Restore',
        ];
    }
}
