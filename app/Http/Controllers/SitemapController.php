<?php

namespace App\Http\Controllers;

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
                'announcementsUpdatedAt' => Announcement::published()->max('updated_at'),
            ])
            ->header('Content-Type', 'text/xml');
    }
}
