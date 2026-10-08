<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.academic', [
            'extracurriculars' => Extracurricular::active()->ordered()->get(),
        ]);
    }
}
