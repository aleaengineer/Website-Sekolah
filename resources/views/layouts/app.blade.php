<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', ($settings['school.name'] ?? 'SMP Negeri Satu Atap I Sidamulih'))</title>
    <meta name="description" content="@yield('description', ($settings['school.description'] ?? ''))">
    <meta property="og:title" content="@yield('title', ($settings['school.name'] ?? 'SMP Negeri Satu Atap I Sidamulih'))">
    <meta property="og:description" content="@yield('description', ($settings['school.description'] ?? ''))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('favicon.svg') }}">
    <meta name="twitter:card" content="summary">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    @include('components.navbar')

    <main class="grow">
        @if (session('success'))
            <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>
