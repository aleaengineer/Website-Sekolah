@extends('admin.layout')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Situs')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="flex flex-col gap-6">
    @csrf
    @method('PUT')

    @foreach ($groups as $group)
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <h2 class="font-serif text-xl font-bold text-navy-900">{{ $group['label'] }}</h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                @foreach ($group['fields'] as $key => $field)
                    <div class="{{ $field['type'] === 'textarea' ? 'sm:col-span-2' : '' }}">
                        <label for="setting-{{ $key }}" class="mb-1.5 block text-sm font-bold text-navy-900">{{ $field['label'] }}</label>

                        @if ($field['type'] === 'textarea')
                            <textarea name="settings[{{ $key }}]" id="setting-{{ $key }}" rows="4"
                                      class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('settings.'.$key, $values[$key] ?? '') }}</textarea>
                        @elseif ($field['type'] === 'select')
                            <select name="settings[{{ $key }}]" id="setting-{{ $key }}"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                                @foreach ($field['options'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('settings.'.$key, $values[$key] ?? '') === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="settings[{{ $key }}]" id="setting-{{ $key }}" value="{{ old('settings.'.$key, $values[$key] ?? '') }}"
                                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @endif
                        @if (! empty($field['hint']))
                            <p class="mt-1 text-xs text-slate-400">{{ $field['hint'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

    <div>
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-4 text-sm font-extrabold text-white shadow-lg transition hover:brightness-110 sm:w-auto">
            Simpan Pengaturan
        </button>
    </div>
</form>
@endsection
