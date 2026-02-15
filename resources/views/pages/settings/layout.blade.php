<div class="flex flex-col gap-5 md:flex-row md:items-start">
    <div class="w-full shrink-0 md:w-48">
        @include('partials.settings-nav')
    </div>

    <div class="flex-1 min-w-0 max-w-lg w-full">
        @if(isset($heading))
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ $heading }}</h2>
        @endif
        @if(isset($subheading))
            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full">
            {{ $slot }}
        </div>
    </div>
</div>