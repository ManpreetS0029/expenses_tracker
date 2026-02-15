<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
    <div class="min-h-screen flex flex-col">
        {{-- Header --}}
        <header class="border-b border-zinc-800 bg-zinc-950/90 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-base sm:text-lg font-bold bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Expenses
                        Tracker</span>
                </div>
                <nav class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-sm px-4 py-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-3 py-2 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 text-sm font-medium transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-sm px-3 sm:px-4 py-2">
                                Sign up
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Hero --}}
        <main class="flex-1">
            <section class="relative overflow-hidden">
                {{-- Background decoration --}}
                <div class="absolute inset-0 -z-10">
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] sm:w-[800px] h-[400px] sm:h-[600px] bg-gradient-to-b from-violet-500/15 via-indigo-500/8 to-transparent rounded-full blur-3xl">
                    </div>
                    <div
                        class="absolute top-1/3 left-0 w-64 h-64 bg-violet-600/5 rounded-full blur-3xl hidden sm:block">
                    </div>
                    <div
                        class="absolute top-1/2 right-0 w-72 h-72 bg-indigo-600/5 rounded-full blur-3xl hidden sm:block">
                    </div>
                </div>

                <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-12 sm:pt-20 lg:pt-24 pb-10 sm:pb-16 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs sm:text-sm font-medium mb-5 sm:mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Simple & Powerful Finance Tracking
                    </div>

                    <h1
                        class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-[1.15] mb-5 sm:mb-6">
                        Take control of<br>
                        <span
                            class="bg-gradient-to-r from-violet-400 via-indigo-400 to-violet-400 bg-clip-text text-transparent">your
                            money</span>
                    </h1>

                    <p
                        class="text-base sm:text-lg lg:text-xl text-zinc-400 max-w-2xl mx-auto mb-7 sm:mb-8 leading-relaxed px-2">
                        Track expenses, log income, set monthly targets, and visualize your finances with beautiful
                        charts and reports.
                    </p>

                    @guest
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-4 sm:px-0">
                            <a href="{{ route('register') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all text-sm sm:text-base">
                                <span>Get started free</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('login') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-zinc-700 text-zinc-300 font-medium hover:bg-zinc-800 hover:border-zinc-600 transition text-sm sm:text-base">
                                Log in
                            </a>
                        </div>
                    @endguest

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all">
                            <span>Go to Dashboard</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </section>

            {{-- Features --}}
            <section class="py-12 sm:py-20 bg-zinc-900/50">
                <div class="max-w-6xl mx-auto px-5 sm:px-6">
                    <div class="text-center mb-8 sm:mb-12">
                        <h2 class="text-xl sm:text-3xl font-bold text-white mb-2 sm:mb-3">Everything you need</h2>
                        <p class="text-sm sm:text-base text-zinc-400">Manage your finances with powerful yet simple
                            tools</p>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                        <div
                            class="group p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-zinc-900 border border-zinc-800 hover:border-violet-700 hover:shadow-lg hover:shadow-violet-500/5 transition-all">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-rose-500/10 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-rose-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-sm sm:text-base text-white mb-1 sm:mb-2">Track Expenses</h3>
                            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed hidden sm:block">Log spending by
                                category and see exactly where your money goes each month.</p>
                            <p class="text-xs text-zinc-500 leading-relaxed sm:hidden">Categorize & track spending</p>
                        </div>

                        <div
                            class="group p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-zinc-900 border border-zinc-800 hover:border-violet-700 hover:shadow-lg hover:shadow-violet-500/5 transition-all">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-sm sm:text-base text-white mb-1 sm:mb-2">Income & Credits</h3>
                            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed hidden sm:block">Record all your
                                income sources and credits to keep your balance accurate.</p>
                            <p class="text-xs text-zinc-500 leading-relaxed sm:hidden">Record income sources</p>
                        </div>

                        <div
                            class="group p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-zinc-900 border border-zinc-800 hover:border-violet-700 hover:shadow-lg hover:shadow-violet-500/5 transition-all">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-violet-500/10 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-violet-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-sm sm:text-base text-white mb-1 sm:mb-2">Reports & Charts</h3>
                            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed hidden sm:block">Visual reports
                                and monthly breakdowns help you understand your finances.</p>
                            <p class="text-xs text-zinc-500 leading-relaxed sm:hidden">Visual finance insights</p>
                        </div>

                        <div
                            class="group p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-zinc-900 border border-zinc-800 hover:border-violet-700 hover:shadow-lg hover:shadow-violet-500/5 transition-all">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/10 flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-amber-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-sm sm:text-base text-white mb-1 sm:mb-2">Monthly Targets</h3>
                            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed hidden sm:block">Set income and
                                expense targets and track your progress throughout the month.</p>
                            <p class="text-xs text-zinc-500 leading-relaxed sm:hidden">Set & track budgets</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            @guest
                <section class="py-12 sm:py-20">
                    <div class="max-w-4xl mx-auto px-5 sm:px-6 text-center">
                        <div
                            class="p-6 sm:p-12 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-violet-600 to-indigo-700 text-white relative overflow-hidden">
                            {{-- Decorative glow --}}
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-violet-400/10 rounded-full blur-3xl">
                            </div>

                            <div class="relative">
                                <h2 class="text-xl sm:text-3xl font-bold mb-3 sm:mb-4">Start managing your money today</h2>
                                <p class="text-violet-200 mb-5 sm:mb-6 max-w-xl mx-auto text-sm sm:text-base">Join now and
                                    take the first step towards better financial health. It's free to get started.</p>
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white text-violet-700 font-semibold hover:bg-violet-50 transition text-sm sm:text-base shadow-lg">
                                    <span>Create free account</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            @endguest
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-800 py-5 sm:py-6 mt-auto">
            <div class="max-w-6xl mx-auto px-5 sm:px-6 text-center text-xs sm:text-sm text-zinc-500">
                {{ config('app.name') }} &middot; Track your finances simply
            </div>
        </footer>
    </div>
</body>

</html>