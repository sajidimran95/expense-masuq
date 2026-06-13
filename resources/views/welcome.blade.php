<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'খরচ হিসাব') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 pb-24 text-white antialiased sm:pb-0">
    <main class="relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1554224154-22dec7ec8818?auto=format&fit=crop&w=1920&q=85');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/65 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-slate-950/10"></div>

        <section class="relative mx-auto flex min-h-[100svh] max-w-7xl flex-col px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            <nav class="flex items-center justify-between gap-3 rounded-full border border-white/10 bg-slate-950/35 p-2 pr-3 shadow-xl backdrop-blur-md sm:bg-transparent sm:p-0 sm:shadow-none sm:backdrop-blur-0">
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/expense-logo.svg') }}" alt="খরচ হিসাব লোগো" class="h-10 w-10 rounded-2xl shadow-lg shadow-blue-500/30 sm:h-11 sm:w-11">
                    <span class="truncate text-sm font-semibold tracking-tight sm:text-lg">Expense Management</span>
                </a>

                <a href="{{ route('admin.login') }}" class="shrink-0 rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-500 sm:px-5 sm:text-sm">
                    অ্যাডমিন লগইন
                </a>
            </nav>

            <div class="flex flex-1 items-end py-8 sm:items-center sm:py-16">
                <div class="relative z-10 w-full max-w-3xl rounded-[2rem] border border-white/10 bg-slate-950/65 p-5 shadow-2xl shadow-slate-950/40 backdrop-blur-md sm:border-0 sm:bg-transparent sm:p-0 sm:shadow-none sm:backdrop-blur-0">
                    <p class="mb-4 inline-flex rounded-full border border-blue-300/40 bg-blue-400/15 px-4 py-2 text-xs font-bold text-blue-100 sm:text-sm">
                        দৈনিক ও মাসিক খরচ হিসাব সফটওয়্যার
                    </p>

                    <h1 class="text-3xl font-black leading-tight tracking-tight drop-shadow-2xl sm:text-5xl lg:text-6xl">
                        প্রতিদিনের খরচ ও মাসিক বাজেট সহজে হিসাব করুন।
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-100 drop-shadow sm:mt-5 sm:text-lg sm:leading-7">
                        দৈনিক খরচ, মাসিক বাজেট, রসিদ, অনুমোদন এবং ক্যাটাগরি রিপোর্ট এক জায়গা থেকে সুন্দরভাবে ম্যানেজ করুন।
                    </p>

                    <div class="mt-6 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:gap-4">
                        <a href="{{ route('admin.login') }}" class="rounded-2xl bg-blue-500 px-6 py-3 text-center text-sm font-bold text-white shadow-xl shadow-blue-500/30 transition hover:bg-blue-400 sm:rounded-full">
                            অ্যাডমিন প্যানেল খুলুন
                        </a>
                        <a href="#features" class="rounded-2xl border border-white/15 px-6 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10 sm:rounded-full">
                            ফিচার দেখুন
                        </a>
                    </div>

                    <div class="mt-6 grid max-w-2xl grid-cols-3 gap-2 sm:mt-10 sm:gap-4">
                        <div class="rounded-2xl border border-white/10 bg-slate-950/45 p-3 shadow-xl backdrop-blur-md sm:rounded-3xl sm:p-4">
                            <p class="text-xs font-semibold text-blue-200 sm:text-sm">দৈনিক খরচ</p>
                            <p class="mt-2 text-lg font-black sm:text-2xl">৳ ১,২৮৪</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">আজকের হিসাব</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-950/45 p-3 shadow-xl backdrop-blur-md sm:rounded-3xl sm:p-4">
                            <p class="text-xs font-semibold text-emerald-200 sm:text-sm">মাসিক বাজেট</p>
                            <p class="text-lg font-black sm:text-2xl">৳ ৪৮,০০০</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">মোট খরচ</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-950/45 p-3 shadow-xl backdrop-blur-md sm:rounded-3xl sm:p-4">
                            <p class="text-xs font-semibold text-amber-200 sm:text-sm">রিপোর্ট</p>
                            <p class="text-lg font-black sm:text-2xl">৩২০+</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">মাসিক এন্ট্রি</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="relative border-t border-white/10 bg-slate-900/80 px-4 py-12 sm:px-6 sm:py-20 lg:px-8">
            <div class="mx-auto grid max-w-7xl gap-4 md:grid-cols-3 lg:gap-6">
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-300">অনুমোদন</p>
                    <h2 class="mt-4 text-2xl font-bold">দ্রুত খরচ যাচাই করুন</h2>
                    <p class="mt-3 text-slate-400">পেন্ডিং, অনুমোদিত ও রিভিউ খরচ সহজে দেখুন।</p>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-300">বাজেট</p>
                    <h2 class="mt-4 text-2xl font-bold">অতিরিক্ত খরচ ধরুন</h2>
                    <p class="mt-3 text-slate-400">মাসিক খরচ, ক্যাটাগরি ও বাজেট অবস্থা এক প্যানেলে দেখুন।</p>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-300">রিপোর্ট</p>
                    <h2 class="mt-4 text-2xl font-bold">পরিষ্কার সারাংশ তৈরি করুন</h2>
                    <p class="mt-3 text-slate-400">দৈনিক ও মাসিক খরচের রিপোর্ট সহজে প্রস্তুত করুন।</p>
                </article>
            </div>
        </section>

        <nav class="fixed inset-x-4 bottom-4 z-50 grid grid-cols-3 rounded-[2rem] border border-white/10 bg-slate-950/85 p-2 text-xs font-bold text-slate-300 shadow-2xl shadow-slate-950/50 backdrop-blur-xl sm:hidden">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 rounded-3xl bg-blue-600 px-3 py-2 text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 11.5 12 5l8 6.5V20a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1v-8.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
                হোম
            </a>
            <a href="#features" class="flex flex-col items-center gap-1 rounded-3xl px-3 py-2 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 19V9m7 10V5m7 14v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                ফিচার
            </a>
            <a href="{{ route('admin.login') }}" class="flex flex-col items-center gap-1 rounded-3xl px-3 py-2 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 9a7 7 0 0 0-14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                লগইন
            </a>
        </nav>
    </main>
</body>
</html>
