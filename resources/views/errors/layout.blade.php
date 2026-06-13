<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') | {{ $siteSetting->title() }}</title>
    <link rel="icon" href="{{ $siteSetting->faviconUrl() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white antialiased">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ $siteSetting->frontBackgroundUrl() }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/90 to-blue-950/80"></div>

        <section class="relative w-full max-w-2xl rounded-[2rem] border border-white/10 bg-white/[0.06] p-6 text-center shadow-2xl shadow-slate-950/50 backdrop-blur-xl sm:p-10">
            <a href="{{ route('home') }}" class="mx-auto mb-8 flex justify-center">
                <img src="{{ $siteSetting->logoUrl() }}" alt="{{ $siteSetting->company_name }} logo" class="h-16 max-w-60 object-contain">
            </a>

            <div class="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-full border border-blue-300/30 bg-blue-500/15 text-4xl font-black text-blue-100">
                @yield('code', '!')
            </div>

            <p class="mb-3 text-sm font-bold uppercase tracking-[0.25em] text-blue-200">@yield('eyebrow', 'Something went wrong')</p>
            <h1 class="text-3xl font-black leading-tight sm:text-5xl">@yield('heading', 'পেজটি পাওয়া যায়নি')</h1>
            <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                @yield('message', 'আপনি যে পেজটি খুঁজছেন সেটি পাওয়া যায়নি অথবা সাময়িকভাবে unavailable।')
            </p>

            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('home') }}" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    হোম পেজে যান
                </a>
                <a href="{{ route('admin.login') }}" class="rounded-2xl border border-white/15 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                    অ্যাডমিন লগইন
                </a>
            </div>
        </section>
    </main>
</body>
</html>
