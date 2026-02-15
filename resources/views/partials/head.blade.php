<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>{{ $title ?? config('app.name') }}</title>

<meta name="description" content="Track your expenses and manage your finances efficiently">
<meta name="theme-color" content="#09090b">

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('fav.png') }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ asset('fav.png') }}" sizes="192x192">
<link rel="apple-touch-icon" href="{{ asset('fav.png') }}">

<!-- Preload critical assets -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

<style>
    #app-loader {
        transition: opacity 0.2s ease-out;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    // Hide loader function
    function hideLoader() {
        var loader = document.getElementById('app-loader');
        if (loader && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden');
            setTimeout(function () { if (loader.parentNode) loader.remove(); }, 300);
        }
    }
    window.addEventListener('load', function () { setTimeout(hideLoader, 100); });
    document.addEventListener('DOMContentLoaded', function () { setTimeout(hideLoader, 500); });
    setTimeout(hideLoader, 3000);
</script>