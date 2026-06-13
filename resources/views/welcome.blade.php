<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'খরচ হিসাব') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white antialiased">
    <main class="relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1554224154-22dec7ec8818?auto=format&fit=crop&w=1920&q=85');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/65 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-slate-950/10"></div>

        <section class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-8 lg:px-8">
            <nav class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/expense-logo.svg') }}" alt="খরচ হিসাব লোগো" class="h-11 w-11 rounded-2xl shadow-lg shadow-blue-500/30">
                    <span class="text-lg font-semibold tracking-tight">Expense Management</span>
                </a>

                <a href="{{ route('admin.login') }}" class="rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-500">
                    অ্যাডমিন লগইন
                </a>
            </nav>

            <div class="flex flex-1 items-center py-16">
                <div class="relative z-10 max-w-3xl">
                    <p class="mb-4 inline-flex rounded-full border border-blue-300/40 bg-blue-400/15 px-4 py-2 text-xs font-bold text-blue-100 sm:text-sm">
                        দৈনিক ও মাসিক খরচ হিসাব সফটওয়্যার
                    </p>

                    <h1 class="text-4xl font-black leading-tight tracking-tight drop-shadow-2xl sm:text-5xl lg:text-6xl">
                        প্রতিদিনের খরচ ও মাসিক বাজেট সহজে হিসাব করুন।
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-100 drop-shadow sm:text-lg">
                        দৈনিক খরচ, মাসিক বাজেট, রসিদ, অনুমোদন এবং ক্যাটাগরি রিপোর্ট এক জায়গা থেকে সুন্দরভাবে ম্যানেজ করুন।
                    </p>

                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('admin.login') }}" class="rounded-full bg-blue-500 px-6 py-3 text-center text-sm font-bold text-white shadow-xl shadow-blue-500/30 transition hover:bg-blue-400">
                            অ্যাডমিন প্যানেল খুলুন
                        </a>
                        <a href="#features" class="rounded-full border border-white/15 px-6 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10">
                            ফিচার দেখুন
                        </a>
                    </div>

                    <div class="mt-10 grid max-w-2xl gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-white/10 bg-slate-950/45 p-4 shadow-xl backdrop-blur-md">
                            <p class="text-xs font-semibold text-blue-200 sm:text-sm">দৈনিক খরচ</p>
                            <p class="mt-2 text-2xl font-black">৳ ১,২৮৪</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">আজকের হিসাব</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-slate-950/45 p-4 shadow-xl backdrop-blur-md">
                            <p class="text-xs font-semibold text-emerald-200 sm:text-sm">মাসিক বাজেট</p>
                            <p class="text-2xl font-black">৳ ৪৮,০০০</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">মোট খরচ</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-slate-950/45 p-4 shadow-xl backdrop-blur-md">
                            <p class="text-xs font-semibold text-amber-200 sm:text-sm">রিপোর্ট</p>
                            <p class="text-2xl font-black">৩২০+</p>
                            <p class="mt-1 text-xs text-slate-300 sm:text-sm">মাসিক এন্ট্রি</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="relative border-t border-white/10 bg-slate-900/80 px-6 py-20 lg:px-8">
            <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-300">অনুমোদন</p>
                    <h2 class="mt-4 text-2xl font-bold">দ্রুত খরচ যাচাই করুন</h2>
                    <p class="mt-3 text-slate-400">পেন্ডিং, অনুমোদিত ও রিভিউ খরচ সহজে দেখুন।</p>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-300">বাজেট</p>
                    <h2 class="mt-4 text-2xl font-bold">অতিরিক্ত খরচ ধরুন</h2>
                    <p class="mt-3 text-slate-400">মাসিক খরচ, ক্যাটাগরি ও বাজেট অবস্থা এক প্যানেলে দেখুন।</p>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.04] p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-300">রিপোর্ট</p>
                    <h2 class="mt-4 text-2xl font-bold">পরিষ্কার সারাংশ তৈরি করুন</h2>
                    <p class="mt-3 text-slate-400">দৈনিক ও মাসিক খরচের রিপোর্ট সহজে প্রস্তুত করুন।</p>
                </article>
            </div>
        </section>
    </main>
</body>
</html>
