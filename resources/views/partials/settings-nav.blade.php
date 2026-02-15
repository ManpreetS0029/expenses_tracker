<nav class="w-full shrink-0 md:w-48" aria-label="{{ __('Settings') }}">
        <ul class="flex gap-1 overflow-x-auto md:flex-col md:gap-0.5 md:overflow-visible pb-2 md:pb-0">
                @php
                        $activeClass = 'bg-gradient-to-r from-violet-600/20 to-indigo-600/20 text-white border border-violet-500/30';
                        $inactiveClass = 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-100';
                @endphp
                <li><a href="{{ route('profile.edit') }}"
                                class="block whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('profile.edit') ? $activeClass : $inactiveClass }}">{{ __('Profile') }}</a>
                </li>
                <li><a href="{{ route('user-password.edit') }}"
                                class="block whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('user-password.edit') ? $activeClass : $inactiveClass }}">{{ __('Password') }}</a>
                </li>
                <li><a href="{{ route('currency.edit') }}"
                                class="block whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('currency.edit') ? $activeClass : $inactiveClass }}">{{ __('Currency') }}</a>
                </li>
        </ul>
</nav>