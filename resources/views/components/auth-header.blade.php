@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-bold text-zinc-50">{{ $title }}</h1>
    <p class="mt-2 text-sm text-zinc-400">{{ $description }}</p>
</div>
