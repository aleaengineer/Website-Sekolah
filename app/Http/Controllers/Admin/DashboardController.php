<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbJalur;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use App\Models\StudentStatistic;
use App\Models\Teacher;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $waves = PpdbWave::withCount('registrations')->orderBy('start_date')->get();
        $jalurNames = PpdbJalur::pluck('name', 'slug')->all();

        return view('admin.dashboard', [
            'stats' => [
                'news' => News::published()->count(),
                'teachers' => Teacher::active()->count(),
                'galleries' => Gallery::count(),
                'extracurriculars' => Extracurricular::active()->count(),
                'ppdbPending' => PpdbRegistration::where('status', PpdbRegistration::STATUS_MENUNGGU)->count(),
                'ppdbTotal' => PpdbRegistration::count(),
                'messagesUnread' => ContactMessage::unread()->count(),
            ],
            'latestRegistrations' => PpdbRegistration::latest()->take(5)->get(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
            'yearlyStats' => StudentStatistic::ordered()->take(10)->get(),
            'ppdbByStatus' => PpdbRegistration::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
            'ppdbWaves' => $waves,
            'ppdbByJalur' => PpdbRegistration::selectRaw('jalur, COUNT(*) as total')->groupBy('jalur')->pluck('total', 'jalur')->all(),
            'jalurNames' => $jalurNames,
            'statusLabels' => PpdbRegistration::STATUSES,
        ]);
    }
}
