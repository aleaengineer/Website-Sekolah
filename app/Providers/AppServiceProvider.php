<?php

namespace App\Providers;

use App\Models\AcademicCalendar;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use App\Models\Setting;
use App\Models\StudentStatistic;
use App\Models\Teacher;
use App\Models\User;
use App\Observers\ActivityObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([AcademicCalendar::class, Agenda::class, Announcement::class, Category::class, ContactMessage::class, Document::class, Extracurricular::class, Gallery::class, News::class, PpdbRegistration::class, PpdbWave::class, StudentStatistic::class, Teacher::class, User::class] as $model) {
            $model::observe(ActivityObserver::class);
        }

        View::composer('*', function ($view): void {
            $view->with('settings', Setting::pluck('value', 'key')->all());
        });

        View::composer(['admin.*'], function ($view): void {
            $view->with('adminBadges', [
                'ppdb' => PpdbRegistration::where('status', PpdbRegistration::STATUS_MENUNGGU)->count(),
                'messages' => ContactMessage::unread()->count(),
            ]);
        });
    }
}
