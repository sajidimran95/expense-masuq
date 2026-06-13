<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>অ্যাডমিন লগইন | {{ config('app.name', 'খরচ হিসাব') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 pb-24 text-slate-900 antialiased sm:pb-0">
    <main class="grid min-h-[100svh] bg-slate-100 lg:grid-cols-[1fr_520px]">
        <section class="relative hidden overflow-hidden bg-slate-900 p-12 text-white lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,_rgba(37,99,235,0.35),_transparent_30%),radial-gradient(circle_at_70%_80%,_rgba(34,197,94,0.25),_transparent_28%)]"></div>
            <div class="relative flex h-full flex-col justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/expense-logo.svg') }}" alt="খরচ হিসাব লোগো" class="h-12 w-12 rounded-2xl shadow-lg shadow-blue-500/30">
                    <span class="text-xl font-bold">Expense Management</span>
                </a>

                <div>
                    <img src="{{ asset('images/admin-login-expense-photo.jpg') }}" alt="খরচ হিসাব ও বাজেট পরিকল্পনা" class="mb-10 max-w-xl rounded-[2rem] shadow-2xl">
                    <p class="mb-4 inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-blue-100">
                        শুধুমাত্র অ্যাডমিন প্রবেশ
                    </p>
                    <h1 class="max-w-2xl text-5xl font-black leading-tight">
                        অ্যাডমিন প্যানেল থেকে খরচ অনুমোদন ম্যানেজ করুন।
                    </h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-slate-300">
                        খরচ যাচাই, ক্লেইম মনিটর এবং বাজেট নিয়ন্ত্রণে রাখতে লগইন করুন।
                    </p>
                </div>
            </div>
        </section>

        <section class="relative flex items-center justify-center overflow-hidden bg-slate-100 px-4 py-5 sm:px-6 sm:py-12">
            <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-br from-blue-600 via-blue-500 to-emerald-400 lg:hidden"></div>
            <div class="absolute inset-x-0 top-0 h-64 bg-[url('/images/admin-login-expense-photo.jpg')] bg-cover bg-center opacity-20 lg:hidden"></div>

            <div class="relative w-full max-w-md">
                <div class="mb-6 lg:hidden">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-full border border-white/20 bg-white/20 p-2 pr-4 shadow-xl backdrop-blur-md">
                        <img src="{{ asset('images/expense-logo.svg') }}" alt="খরচ হিসাব লোগো" class="h-10 w-10 rounded-2xl">
                        <span class="truncate text-base font-bold text-white">Expense Management</span>
                    </a>
                </div>

                <div class="rounded-[2rem] bg-white p-5 shadow-2xl shadow-slate-900/15 sm:p-8">
                    <div class="mb-6 sm:mb-8">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">অ্যাডমিন লগইন</p>
                        <h2 class="mt-3 text-2xl font-black text-slate-950 sm:text-3xl">স্বাগতম</h2>
                        <p class="mt-2 text-sm text-slate-500">চালিয়ে যেতে আপনার অ্যাডমিন অ্যাকাউন্ট ব্যবহার করুন।</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4 sm:space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-slate-700">ইমেইল ঠিকানা</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-base text-slate-950 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="admin@example.com">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold text-slate-700">পাসওয়ার্ড</label>
                            <input id="password" name="password" type="password" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-base text-slate-950 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="password">
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                আমাকে মনে রাখুন
                            </label>
                            <a href="{{ route('home') }}" class="text-sm font-bold text-blue-600 hover:text-blue-500">হোমে ফিরুন</a>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-blue-600 px-5 py-4 text-sm font-black text-white shadow-xl shadow-blue-600/25 transition hover:bg-blue-500">
                            অ্যাডমিন প্যানেলে লগইন করুন
                        </button>
                    </form>

                    <p class="mt-6 rounded-2xl bg-slate-50 px-4 py-3 text-xs font-medium text-slate-500">
                        ডেমো অ্যাডমিন: <span class="font-bold text-slate-700">admin@example.com</span> / <span class="font-bold text-slate-700">password</span>
                    </p>
                </div>
            </div>
        </section>

        <nav class="fixed inset-x-4 bottom-4 z-50 grid grid-cols-2 rounded-[2rem] border border-white/10 bg-slate-950/90 p-2 text-xs font-bold text-slate-300 shadow-2xl shadow-slate-950/50 backdrop-blur-xl sm:hidden">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 rounded-3xl px-3 py-2 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 11.5 12 5l8 6.5V20a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1v-8.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
                হোম
            </a>
            <a href="{{ route('admin.login') }}" class="flex flex-col items-center gap-1 rounded-3xl bg-blue-600 px-3 py-2 text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 9a7 7 0 0 0-14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                লগইন
            </a>
        </nav>
    </main>
</body>
</html>
