<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        return view('pages.agendas-index', [
            'upcoming' => Agenda::upcoming()->take(6)->get(),
            'agendas' => Agenda::published()->recent()->paginate(9),
        ]);
    }

    public function show(Agenda $agenda): View
    {
        abort_unless($agenda->is_published, 404);

        return view('pages.agenda-show', [
            'agenda' => $agenda,
            'others' => Agenda::published()->recent()->where('id', '!=', $agenda->id)->take(3)->get(),
        ]);
    }
}
