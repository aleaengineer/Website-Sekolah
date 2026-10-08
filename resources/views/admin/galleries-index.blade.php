@extends('admin.layout')

@section('title', 'Galeri')
@section('heading', 'Galeri')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $galleries->total() }} foto</p>
    <a href="{{ route('admin.galleries.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Foto</a>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($galleries as $gallery)
        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            @if ($gallery->image)
                <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->title }}" class="h-44 w-full object-cover">
            @else
                <x-cover class="h-44" :title="$gallery->title" :seed="$gallery->id" />
            @endif
            <div class="p-4">
                <p class="font-bold text-navy-900">{{ \Illuminate\Support\Str::limit($gallery->title, 50) }}</p>
                <p class="text-xs text-slate-500">Urutan {{ $gallery->sort_order }} &bull; {{ $gallery->taken_at?->translatedFormat('M Y') ?? '—' }}</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-3 rounded-3xl bg-white p-10 text-center text-slate-500">Belum ada foto galeri.</p>
    @endforelse
</div>

<div class="mt-6">{{ $galleries->links() }}</div>
@endsection
