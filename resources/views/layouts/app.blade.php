<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
    <div id="app-loader"
        class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-zinc-950 transition-opacity duration-300">
        <div
            class="h-6 w-6 animate-spin rounded-full border-2 border-zinc-800 border-t-violet-500">
        </div>
    </div>

    {{-- Mobile menu overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" aria-hidden="true"
        style="display: none;"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 transition-transform duration-200 ease-out -translate-x-full lg:translate-x-0">
        {{-- Logo --}}
        <div class="flex h-14 shrink-0 items-center justify-between px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-zinc-900 dark:text-white">
                <span class="flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/30">
                    <svg class="size-4 fill-white" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"
                            clip-rule="evenodd" />
                        <path fill-rule="evenodd"
                            d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text-sm font-bold tracking-tight bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Expenses</span>
            </a>
            <button type="button" id="sidebar-close"
                class="flex size-8 items-center justify-center rounded-lg text-zinc-400 hover:bg-zinc-800 hover:text-zinc-100 transition-colors lg:hidden"
                aria-label="Close sidebar">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3" aria-label="Main">
            <ul class="space-y-0.5">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'color' => 'text-violet-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />'],
                        ['route' => 'categories', 'label' => 'Categories', 'color' => 'text-amber-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />'],
                        ['route' => 'expenses', 'label' => 'Expenses', 'color' => 'text-rose-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />'],
                        ['route' => 'credits', 'label' => 'Credits', 'color' => 'text-emerald-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                        ['route' => 'monthly-targets', 'label' => 'Targets', 'color' => 'text-cyan-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />'],
                        ['route' => 'reports', 'label' => 'Reports', 'color' => 'text-blue-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'],
                    ];
                @endphp
                @foreach($navItems as $nav)
                    <li>
                        <a href="{{ route($nav['route']) }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ request()->routeIs($nav['route']) ? 'bg-gradient-to-r from-violet-600/20 to-indigo-600/20 text-white border border-violet-500/30' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-100' }}">
                            <svg class="size-[18px] shrink-0 {{ request()->routeIs($nav['route']) ? 'text-violet-400' : $nav['color'] }}" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">{!! $nav['icon'] !!}</svg>
                            {{ $nav['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 border-t border-zinc-200 pt-4 dark:border-zinc-800">
                <p class="mb-2 px-3 text-[11px] font-medium uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                    Account</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ request()->routeIs('profile.edit', 'user-password.edit', 'currency.edit') ? 'bg-gradient-to-r from-violet-600/20 to-indigo-600/20 text-white border border-violet-500/30' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-100' }}">
                            <svg class="size-[18px] shrink-0 {{ request()->routeIs('profile.edit', 'user-password.edit', 'currency.edit') ? 'text-violet-400' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- User section at bottom --}}
        <div class="shrink-0 border-t border-zinc-200 p-3 dark:border-zinc-800">
            <div class="relative">
                <button type="button" id="user-menu-button"
                    class="flex w-full items-center gap-3 rounded-lg px-2 py-2 text-left transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    aria-expanded="false" aria-haspopup="true">
                    <span
                        class="flex size-8 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 text-xs font-bold text-white shadow-lg shadow-violet-500/20">{{ auth()->user()->initials() }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>
                    </div>
                    <svg class="size-4 shrink-0 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="user-menu-dropdown"
                    class="hidden absolute bottom-full left-0 right-0 mb-1 rounded-lg border border-zinc-200 bg-white py-1 shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
                    role="menu">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-200 dark:hover:bg-zinc-700">Settings</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full px-3 py-2 text-left text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-200 dark:hover:bg-zinc-700"
                            data-test="logout-button">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main content area --}}
    <div class="min-h-screen lg:pl-60">
        {{-- Mobile header with menu button only --}}
        <header
            class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b border-zinc-200 bg-white/80 px-4 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80 lg:hidden">
            <button type="button" id="sidebar-toggle"
                class="-ml-1 flex size-9 items-center justify-center rounded-lg text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800"
                aria-label="Open menu">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <span class="text-sm font-semibold text-zinc-900 dark:text-white">@yield('title', 'Expenses')</span>
        </header>

        <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto w-full min-w-0 max-w-5xl">
                @if(session('success'))
                    <div id="toast-success"
                        class="fixed top-4 right-4 z-50 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white shadow-lg dark:bg-zinc-100 dark:text-zinc-900"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggle = document.getElementById('sidebar-toggle');
            var userBtn = document.getElementById('user-menu-button');
            var userDropdown = document.getElementById('user-menu-dropdown');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                if (overlay) { overlay.style.display = 'block'; overlay.setAttribute('aria-hidden', 'false'); }
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                if (overlay) { overlay.style.display = 'none'; overlay.setAttribute('aria-hidden', 'true'); }
                document.body.style.overflow = '';
            }

            if (toggle) toggle.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            var closeBtn = document.getElementById('sidebar-close');
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);

            // Close sidebar when a nav link is clicked on mobile
            sidebar.querySelectorAll('nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 1024) closeSidebar();
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) closeSidebar();
            });

            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', function () { userDropdown.classList.add('hidden'); });
            }

            function hideLoader() {
                var el = document.getElementById('app-loader');
                if (el) { el.style.opacity = '0'; setTimeout(function () { el.remove(); }, 300); }
            }
            window.addEventListener('load', function () { setTimeout(hideLoader, 150); });
            setTimeout(hideLoader, 2500);

            // Toast auto-hide
            var toast = document.getElementById('toast-success');
            if (toast) setTimeout(function () { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function () { toast.remove(); }, 300); }, 3000);
        })();
    </script>
    @stack('scripts')
</body>

</html>