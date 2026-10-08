@extends('admin.layout')

@section('title', 'Berita')
@section('heading', 'Berita')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-slate-500">{{ $news->total() }} berita</p>
    <div class="flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-navy-900 transition hover:bg-slate-50">Kelola Kategori</a>
        <a href="{{ route('admin.news.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tulis Berita</a>
    </div>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Judul</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Terbit</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($news as $article)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-navy-900">{{ \Illuminate\Support\Str::limit($article->title, 60) }}
                            @if ($article->category)
                                <span class="ml-2 rounded-full bg-sky-100 px-2.5 py-0.5 text-[11px] font-bold text-sky-800">{{ $article->category->name }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($article->is_published)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Tayang</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Draf</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $article->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('news.show', $article) }}" target="_blank" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200">Lihat</a>
                                <a href="{{ route('admin.news.edit', $article) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.news.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada berita. Klik "Tulis Berita" untuk menambah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $news->links() }}</div>
@endsection
