@props(['eyebrow' => null, 'align' => 'center'])

<div class="{{ $align === 'center' ? 'text-center' : 'text-left' }} {{ $attributes->get('class') }}">
    @if ($eyebrow)
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-2 font-serif text-3xl font-bold text-navy-900 sm:text-4xl">{{ $slot }}</h2>
    <div class="mt-4 flex items-center gap-2 {{ $align === 'center' ? 'justify-center' : '' }}" aria-hidden="true">
        <span class="h-1 w-10 rounded-full bg-amber-400"></span>
        <span class="h-1 w-4 rounded-full bg-emerald-500"></span>
        <span class="h-1 w-10 rounded-full bg-navy-800"></span>
    </div>
</div>
