<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\Agenda;
use App\Models\Document;
use App\Models\Extracurricular;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.academic', [
            'extracurriculars' => Extracurricular::active()->ordered()->get(),
            'calendars' => AcademicCalendar::published()->get(),
            'calendarCategories' => AcademicCalendar::CATEGORIES,
            'documents' => Document::published()->ordered()->get(),
            'documentCategories' => Document::CATEGORIES,
            'upcomingAgendas' => Agenda::upcoming()->take(4)->get(),
        ]);
    }
}
