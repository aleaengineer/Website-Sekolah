@extends('layouts.app')

@section('title', 'Galeri — '.($settings['school.name'] ?? ''))

@section('description', 'Dokumentasi foto kegiatan SMP Negeri Satu Atap I Sidamulih.')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Dokumentasi</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Galeri Kegiatan</h1>
        <p class="mt-2 text-slate-200">Momen pembelajaran, ekstrakurikuler, dan perayaan di sekolah kami.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($galleries as $index => $item)
            <figure class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:shadow-lg">
                <x-cover class="h-56" :title="$item->title" :seed="$index" :image="$item->image" />
                <figcaption class="p-5">
                    <p class="font-extrabold text-navy-900">{{ $item->title }}</p>
                    @if ($item->description)
                        <p class="mt-1 text-sm text-slate-600">{{ $item->description }}</p>
                    @endif
                    @if ($item->taken_at)
                        <p class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-600">{{ $item->taken_at->translatedFormat('M Y') }}</p>
                    @endif
                </figcaption>
            </figure>
        @empty
            <p class="col-span-3 rounded-3xl bg-white p-8 text-center text-slate-500">Belum ada foto galeri. Dokumentasi segera dilengkapi.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $galleries->links() }}
    </div>
</section>
@endsection
