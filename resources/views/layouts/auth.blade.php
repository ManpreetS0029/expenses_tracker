<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="Track your expenses and manage your finances efficiently">
    <meta name="theme-color" content="#09090b">
    <link rel="icon" type="image/png" href="{{ asset('fav.png') }}" sizes="32x32">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
    <div class="flex min-h-screen">
        {{-- Left decorative panel (hidden on mobile) --}}
        <div class="relative hidden w-1/2 overflow-hidden lg:block">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-950 via-zinc-950 to-indigo-950"></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(139,92,246,0.15),_transparent_60%)]">
            </div>
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(99,102,241,0.1),_transparent_60%)]">
            </div>

            {{-- Floating decorative elements --}}
            <div class="absolute top-1/4 left-1/4 h-72 w-72 rounded-full bg-violet-500/5 blur-3xl"></div>
            <div class="absolute bottom-1/3 right-1/4 h-56 w-56 rounded-full bg-indigo-500/5 blur-3xl"></div>

            {{-- Content --}}
            <div class="relative flex h-full flex-col items-center justify-center px-12">
                <div class="flex items-center gap-3 mb-8">
                    <span
                        class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 shadow-xl shadow-violet-500/30">
                        <svg class="size-6 fill-white" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span
                        class="text-2xl font-bold bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Expenses
                        Tracker</span>
                </div>
                <p class="text-center text-lg text-zinc-400 max-w-sm leading-relaxed">
                    Take control of your finances. Track expenses, monitor budgets, and achieve your financial goals.
                </p>
                <div class="mt-12 grid grid-cols-3 gap-6 text-center">
                    <div>
                        <p class="text-2xl font-bold text-violet-400">Smart</p>
                        <p class="text-xs text-zinc-500 mt-1">Analytics</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-emerald-400">Secure</p>
                        <p class="text-xs text-zinc-500 mt-1">Data</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-cyan-400">Simple</p>
                        <p class="text-xs text-zinc-500 mt-1">Interface</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2">
            <div class="w-full max-w-sm">
                {{-- Mobile logo --}}
                <div class="mb-8 flex items-center justify-center gap-2.5 lg:hidden">
                    <span
                        class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/30">
                        <svg class="size-5 fill-white" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span
                        class="text-lg font-bold bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Expenses
                        Tracker</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>