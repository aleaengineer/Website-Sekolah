<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.announcements', [
            'announcements' => Announcement::published()->recent()->paginate(10),
        ]);
    }
}
