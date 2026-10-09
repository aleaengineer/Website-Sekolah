<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\News;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'news' => News::published()->recent()->get(['slug', 'updated_at']),
                'agendas' => Agenda::published()->recent()->get(['slug', 'updated_at']),
                'announcementsUpdatedAt' => Announcement::published()->max('updated_at'),
                'staticLastmod' => max([
                    News::published()->max('updated_at'),
                    Agenda::published()->max('updated_at'),
                    Announcement::published()->max('updated_at'),
                    now()->toDateTimeString(),
                ]),
            ])
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        return response("User-agent: *\nDisallow: /admin\nDisallow: /login\nDisallow: /cari\n\nSitemap: ".route('sitemap')."\n")
            ->header('Content-Type', 'text/plain');
    }
}
