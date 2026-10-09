<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\News;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $q = trim($validated['q']);
        $like = "%{$q}%";

        $news = News::published()->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('excerpt', 'like', $like))->recent()->take(8)->get();
        $agendas = Agenda::published()->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('description', 'like', $like))->recent()->take(8)->get();
        $announcements = Announcement::published()->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('content', 'like', $like))->recent()->take(8)->get();
        $documents = Document::published()->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('description', 'like', $like))->ordered()->take(8)->get();
        $teachers = Teacher::active()->where(fn ($query) => $query->where('name', 'like', $like)->orWhere('subject', 'like', $like))->ordered()->take(8)->get();

        return view('pages.search', [
            'q' => $q,
            'groups' => [
                [
                    'label' => 'Berita',
                    'items' => $news->map(fn (News $item) => [
                        'url' => route('news.show', $item),
                        'title' => $item->title,
                        'desc' => Str::limit($item->excerpt ?? '', 120),
                    ]),
                ],
                [
                    'label' => 'Agenda',
                    'items' => $agendas->map(fn (Agenda $item) => [
                        'url' => route('agendas.show', $item),
                        'title' => $item->title,
                        'desc' => $item->start_at->translatedFormat('d M Y').($item->location ? ' - '.$item->location : ''),
                    ]),
                ],
                [
                    'label' => 'Pengumuman',
                    'items' => $announcements->map(fn (Announcement $item) => [
                        'url' => route('announcements'),
                        'title' => $item->title,
                        'desc' => Str::limit($item->content, 120),
                    ]),
                ],
                [
                    'label' => 'Dokumen',
                    'items' => $documents->map(fn (Document $item) => [
                        'url' => route('academic'),
                        'title' => $item->title,
                        'desc' => $item->description ? Str::limit($item->description, 120) : 'Unduhan dokumen',
                    ]),
                ],
                [
                    'label' => 'Guru dan Tendik',
                    'items' => $teachers->map(fn (Teacher $item) => [
                        'url' => route('profile'),
                        'title' => $item->name,
                        'desc' => trim($item->position.' - '.$item->subject, ' -'),
                    ]),
                ],
            ],
        ]);
    }
}
