<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-900">
    <!-- App Loading Screen -->
    <div id="app-loader">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" fill="white"/>
        </svg>
        <span class="loader-text">Expenses Tracker</span>
        <div class="loader-spinner"></div>
    </div>
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-950">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item :href="route('categories')" :current="request()->routeIs('categories')"
                    wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>

                    </x-slot:icon>
                    {{ __('Categories') }}
                </flux:sidebar.item>

                <flux:sidebar.item :href="route('expenses')" :current="request()->routeIs('expenses')" wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Expenses') }}
                </flux:sidebar.item>

                <flux:sidebar.item :href="route('credits')" :current="request()->routeIs('credits')" wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18 9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.28m5.94 2.28-2.28 5.941" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Credits') }}
                </flux:sidebar.item>

                <flux:sidebar.item :href="route('monthly-targets')" :current="request()->routeIs('monthly-targets')"
                    wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Monthly Targets') }}
                </flux:sidebar.item>

                <flux:sidebar.item :href="route('reports')" :current="request()->routeIs('reports')" wire:navigate>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </x-slot:icon>
                    {{ __('Reports') }}
                </flux:sidebar.item>

            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <!-- PWA Install Button -->
        <div id="pwa-install-btn" style="display: none;" class="px-4 mb-4">
            <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Install App
            </button>
        </div>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>


    <flux:header class="lg:hidden z-50 sticky top-0">
        <flux:sidebar.toggle class="lg:hidden !flex" inset="left" icon="bars-2" />

        <flux:spacer />

        <!-- Mobile PWA Install Button -->
        <button id="pwa-install-btn-mobile" style="display: none;" class="inline-flex items-center justify-center p-2 mr-2 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150" title="Install App">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
        </button>

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()">
                <x-slot:icon-trailing>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </x-slot:icon-trailing>
            </flux:profile>

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" wire:navigate>
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </x-slot:icon>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" class="w-full cursor-pointer" data-test="logout-button">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </x-slot:icon>
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    <!-- Mobile PWA Install Guide Modal -->
    <div id="pwa-install-guide-modal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open" x-cloak>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="open = false; document.getElementById('pwa-install-guide-modal').classList.add('hidden')"></div>
        <div class="fixed inset-x-4 bottom-4 sm:inset-auto sm:bottom-auto sm:left-1/2 sm:top-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-md w-auto bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl p-6 z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Install Expenses Tracker</h3>
                <button @click="open = false; document.getElementById('pwa-install-guide-modal').classList.add('hidden')" class="p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Browser Detection and Instructions -->
            <div id="install-instructions" class="space-y-4">
                <!-- Chrome Instructions -->
                <div id="chrome-instructions" class="hidden">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 via-yellow-500 to-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>
                        <span class="font-medium text-zinc-900 dark:text-white">Chrome Browser</span>
                    </div>
                    <ol class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300">
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span>Tap the <strong class="text-zinc-900 dark:text-white">three dots menu</strong> (⋮) at the top right</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span>Look for <strong class="text-zinc-900 dark:text-white">"Install app"</strong> or <strong class="text-zinc-900 dark:text-white">"Add to Home screen"</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span>Tap <strong class="text-zinc-900 dark:text-white">"Install"</strong> to add the app</span>
                        </li>
                    </ol>
                </div>

                <!-- Samsung Internet Instructions -->
                <div id="samsung-instructions" class="hidden">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <span class="font-medium text-zinc-900 dark:text-white">Samsung Internet</span>
                    </div>
                    <ol class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300">
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span>Tap the <strong class="text-zinc-900 dark:text-white">menu button</strong> (☰) at the bottom</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span>Tap <strong class="text-zinc-900 dark:text-white">"Add page to"</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span>Select <strong class="text-zinc-900 dark:text-white">"Home screen"</strong></span>
                        </li>
                    </ol>
                </div>

                <!-- Safari iOS Instructions -->
                <div id="safari-instructions" class="hidden">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.5 14.5l-7-3.5-3.5-7 7 3.5 3.5 7z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-zinc-900 dark:text-white">Safari (iPhone/iPad)</span>
                    </div>
                    <ol class="space-y-3 text-sm text-zinc-600 dark:text-zinc-300">
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span>Tap the <strong class="text-zinc-900 dark:text-white">Share button</strong> (square with arrow) at the bottom</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span>Scroll down and tap <strong class="text-zinc-900 dark:text-white">"Add to Home Screen"</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span>Tap <strong class="text-zinc-900 dark:text-white">"Add"</strong> in the top right</span>
                        </li>
                    </ol>
                </div>

                <!-- Generic Instructions -->
                <div id="generic-instructions" class="hidden">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300 mb-3">
                        Use your browser's menu to add this app to your home screen:
                    </p>
                    <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Look for "Install app" or "Add to Home screen"
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Check the browser menu (three dots or lines)
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Info Note -->
            <div class="mt-4 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                <p class="text-xs text-indigo-700 dark:text-indigo-300">
                    <strong>Note:</strong> For the best experience, make sure you're accessing this site over HTTPS.
                </p>
            </div>

            <button @click="open = false; document.getElementById('pwa-install-guide-modal').classList.add('hidden'); localStorage.setItem('pwa-install-dismissed', 'true')" class="w-full mt-4 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Got it!
            </button>
        </div>
    </div>

    <!-- Mobile Install Banner (shown on first visit on mobile) -->
    <div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 z-40 hidden lg:hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-4 flex items-center gap-3">
            <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-semibold text-sm">Install App</p>
                <p class="text-indigo-100 text-xs">Add to home screen for quick access</p>
            </div>
            <div class="flex gap-2">
                <button id="pwa-banner-install" class="px-3 py-1.5 bg-white text-indigo-600 text-sm font-medium rounded-lg hover:bg-indigo-50 transition-colors">
                    Install
                </button>
                <button id="pwa-banner-dismiss" class="p-1.5 text-white/70 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Detect browser and show appropriate instructions
        function detectBrowserAndShowInstructions() {
            const ua = navigator.userAgent.toLowerCase();
            const modal = document.getElementById('pwa-install-guide-modal');
            
            // Hide all instruction sets first
            document.querySelectorAll('#install-instructions > div').forEach(el => el.classList.add('hidden'));
            
            if (ua.includes('samsungbrowser')) {
                document.getElementById('samsung-instructions').classList.remove('hidden');
            } else if (ua.includes('crios') || (ua.includes('chrome') && !ua.includes('edg'))) {
                document.getElementById('chrome-instructions').classList.remove('hidden');
            } else if (ua.includes('safari') && !ua.includes('chrome') && (ua.includes('iphone') || ua.includes('ipad'))) {
                document.getElementById('safari-instructions').classList.remove('hidden');
            } else {
                document.getElementById('generic-instructions').classList.remove('hidden');
            }
        }

        function showInstallGuideModal() {
            const modal = document.getElementById('pwa-install-guide-modal');
            detectBrowserAndShowInstructions();
            modal.classList.remove('hidden');
            modal.__x.$data.open = true;
        }

        function showInstallBanner() {
            const banner = document.getElementById('pwa-install-banner');
            const dismissed = localStorage.getItem('pwa-install-dismissed');
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Only show on mobile, if not dismissed, and not already installed
            if (banner && isMobile && !dismissed && !isStandalone) {
                setTimeout(() => {
                    banner.classList.remove('hidden');
                }, 2000);
            }
        }

        // Setup banner buttons
        document.addEventListener('DOMContentLoaded', function() {
            const installBtn = document.getElementById('pwa-banner-install');
            const dismissBtn = document.getElementById('pwa-banner-dismiss');
            const banner = document.getElementById('pwa-install-banner');

            if (installBtn) {
                installBtn.addEventListener('click', function() {
                    // If we have the deferred prompt, use it
                    if (window.deferredPrompt) {
                        window.deferredPrompt.prompt();
                        window.deferredPrompt.userChoice.then(function(choiceResult) {
                            if (choiceResult.outcome === 'accepted') {
                                banner.classList.add('hidden');
                            }
                            window.deferredPrompt = null;
                        });
                    } else {
                        // Otherwise show the manual install guide
                        banner.classList.add('hidden');
                        showInstallGuideModal();
                    }
                });
            }

            if (dismissBtn) {
                dismissBtn.addEventListener('click', function() {
                    banner.classList.add('hidden');
                    localStorage.setItem('pwa-install-dismissed', 'true');
                });
            }

            // Show banner on mobile
            showInstallBanner();
        });
    </script>

    @fluxScripts
</body>

</html>