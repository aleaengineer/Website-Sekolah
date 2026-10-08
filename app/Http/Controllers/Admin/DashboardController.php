<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbRegistration;
use App\Models\StudentStatistic;
use App\Models\Teacher;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
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
        ]);
    }
}
