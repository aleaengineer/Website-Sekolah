<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.profile', [
            'teachers' => Teacher::active()->ordered()->get(),
        ]);
    }
}
