<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\PpdbRegistration;
use App\Models\Setting;
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
        View::composer(['layouts.*', 'pages.*'], function ($view): void {
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
