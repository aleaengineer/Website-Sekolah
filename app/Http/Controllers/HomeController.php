<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Teacher;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'heroSlides' => HeroSlide::active()->ordered()->get(),
            'latestNews' => News::published()->recent()->take(3)->get(),
            'announcements' => Announcement::published()->recent()->take(4)->get(),
            'galleries' => Gallery::ordered()->take(6)->get(),
            'extracurriculars' => Extracurricular::active()->ordered()->take(6)->get(),
            'teacherCount' => Teacher::active()->count(),
            'newsCount' => News::published()->count(),
        ]);
    }
}
