<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
        @if (Route::has('login'))
            <nav class="fixed right-4 top-4 z-10 rounded-full bg-white/90 px-4 py-2 shadow-lg shadow-slate-200/50 backdrop-blur dark:bg-slate-900/90 dark:shadow-black/20">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 dark:text-slate-100 dark:hover:bg-slate-800">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 dark:text-slate-100 dark:hover:bg-slate-800">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 dark:text-slate-100 dark:hover:bg-slate-800">Register</a>
                    @endif
                @endauth
            </nav>
        @endif

        <main class="flex min-h-screen items-center justify-center px-6 py-12">
            <section class="mx-auto w-full max-w-4xl rounded-[2rem] border border-slate-200 bg-white/95 p-12 shadow-[0_28px_80px_-40px_rgba(15,23,42,0.35)] backdrop-blur-xl dark:border-slate-700 dark:bg-slate-950/95">
                <div class="space-y-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">Government Auction</p>
                    <h1 class="text-5xl font-semibold leading-tight tracking-tight text-slate-900 dark:text-slate-50">A clean auction experience for bidders, buyers, and officers.</h1>
                    <p class="max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">Explore live auctions, save favorites to your watchlist, and log in to manage bids with a simple, modern interface.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('auctions.index') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-950 dark:hover:bg-slate-200">Browse Auctions</a>
                        @guest
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">Log in</a>
                        @endguest
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
