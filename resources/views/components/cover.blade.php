@props(['title' => '', 'seed' => 0, 'image' => null])

@php
    $gradients = [
        'from-navy-800 via-navy-700 to-emerald-700',
        'from-emerald-700 via-emerald-600 to-teal-500',
        'from-amber-500 via-orange-500 to-rose-500',
        'from-sky-700 via-navy-700 to-navy-900',
    ];
    $gradient = $gradients[abs((int) $seed) % count($gradients)];
@endphp

@if ($image)
    <img src="{{ asset('storage/'.$image) }}" alt="{{ $title }}" {{ $attributes->merge(['class' => 'w-full object-cover']) }} loading="lazy">
@else
    <div {{ $attributes->merge(['class' => "flex items-center justify-center bg-gradient-to-br {$gradient} p-6 text-center"]) }}>
        <div>
            <x-logo class="mx-auto h-12 w-12 opacity-90" />
            <p class="mt-3 line-clamp-3 text-sm font-bold text-white">{{ $title }}</p>
        </div>
    </div>
@endif
