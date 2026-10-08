<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.gallery', [
            'galleries' => Gallery::ordered()->paginate(12),
        ]);
    }
}
