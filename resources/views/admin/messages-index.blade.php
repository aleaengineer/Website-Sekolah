@extends('admin.layout')

@section('title', 'Pesan Masuk')
@section('heading', 'Pesan Masuk')

@section('content')
<div class="flex flex-col gap-4">
    @forelse ($messages as $msg)
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 {{ $msg->is_read ? 'ring-slate-200' : 'ring-amber-300' }}">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <p class="font-extrabold text-navy-900">{{ $msg->name }} <span class="font-normal text-slate-500">— {{ $msg->contact }}</span></p>
                <p class="text-xs text-slate-400">{{ $msg->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
            @if ($msg->subject)
                <p class="mt-1 text-sm font-bold text-emerald-700">{{ $msg->subject }}</p>
            @endif
            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $msg->message }}</p>
            <div class="mt-3 flex gap-2">
                <form action="{{ route('admin.messages.update', $msg) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_read" value="{{ $msg->is_read ? 0 : 1 }}">
                    <button type="submit" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200">
                        {{ $msg->is_read ? 'Tandai belum dibaca' : 'Tandai sudah dibaca' }}
                    </button>
                </form>
                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="rounded-3xl bg-white p-10 text-center text-slate-500">Belum ada pesan masuk.</p>
    @endforelse
</div>

<div class="mt-6">{{ $messages->links() }}</div>
@endsection
