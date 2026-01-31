<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>{{ $title ?? config('app.name') }}</title>

<!-- PWA Meta Tags -->
<meta name="application-name" content="Expenses Tracker">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Expenses Tracker">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#6366f1" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">
<meta name="description" content="Track your expenses and manage your finances efficiently">
<link rel="manifest" href="/manifest.json">

<!-- Favicon - single PNG works on all devices (browsers scale as needed) -->
<link rel="icon" type="image/png" href="{{ asset('fav.png') }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ asset('fav.png') }}" sizes="192x192">
<link rel="apple-touch-icon" href="{{ asset('fav.png') }}">


<!-- Preload critical assets -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Loading Screen Styles -->
<style>
    #app-loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease-out;
    }
    .dark #app-loader {
        background: linear-gradient(135deg, #18181b 0%, #27272a 100%);
    }
    #app-loader.hidden {
        opacity: 0;
        pointer-events: none;
    }
    #app-loader svg {
        width: 80px;
        height: 80px;
        animation: pulse 1.5s ease-in-out infinite;
    }
    #app-loader .loader-text {
        color: white;
        font-family: 'Instrument Sans', system-ui, sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1rem;
    }
    #app-loader .loader-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-top: 1.5rem;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<!-- Load Chart.js with defer for better performance -->
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

<!-- PWA Service Worker Registration -->
<script>
    // Register Service Worker early
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js', { scope: '/' })
            .then(registration => {
                console.log('SW registered:', registration.scope);
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New content available, notify user
                            console.log('New content available, please refresh.');
                        }
                    });
                });
            })
            .catch(error => console.log('SW registration failed:', error));
    }

    // PWA Install Prompt - Enhanced for mobile
    // Make deferredPrompt globally accessible
    window.deferredPrompt = null;
    let installButtonClickHandlers = [];
    
    function showInstallButtons() {
        // Desktop sidebar button
        const installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            installBtn.style.display = 'block';
        }
        // Mobile header button
        const installBtnMobile = document.getElementById('pwa-install-btn-mobile');
        if (installBtnMobile) {
            installBtnMobile.style.display = 'flex';
        }
        // Hide the manual install banner if native prompt is available
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.classList.add('hidden');
        }
    }
    
    function hideInstallButtons() {
        const installBtn = document.getElementById('pwa-install-btn');
        const installBtnMobile = document.getElementById('pwa-install-btn-mobile');
        const banner = document.getElementById('pwa-install-banner');
        if (installBtn) installBtn.style.display = 'none';
        if (installBtnMobile) installBtnMobile.style.display = 'none';
        if (banner) banner.classList.add('hidden');
    }
    
    async function handleInstallClick() {
        if (window.deferredPrompt) {
            window.deferredPrompt.prompt();
            const { outcome } = await window.deferredPrompt.userChoice;
            console.log('User response:', outcome);
            if (outcome === 'accepted') {
                hideInstallButtons();
            }
            window.deferredPrompt = null;
        } else {
            // Show manual install guide for browsers that don't support beforeinstallprompt
            if (typeof showInstallGuideModal === 'function') {
                showInstallGuideModal();
            }
        }
    }
    
    function setupInstallButtons() {
        // Remove old listeners
        installButtonClickHandlers.forEach(({ element, handler }) => {
            element.removeEventListener('click', handler);
        });
        installButtonClickHandlers = [];
        
        // Desktop button (has nested button element)
        const installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            const button = installBtn.querySelector('button');
            if (button) {
                button.addEventListener('click', handleInstallClick);
                installButtonClickHandlers.push({ element: button, handler: handleInstallClick });
            }
        }
        
        // Mobile button (is the button element itself)
        const installBtnMobile = document.getElementById('pwa-install-btn-mobile');
        if (installBtnMobile) {
            installBtnMobile.addEventListener('click', handleInstallClick);
            installButtonClickHandlers.push({ element: installBtnMobile, handler: handleInstallClick });
        }
    }
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        window.deferredPrompt = e;
        console.log('Install prompt captured (native)');
        
        // Show install buttons with delay to ensure DOM is ready
        setTimeout(() => {
            showInstallButtons();
            setupInstallButtons();
        }, 500);
    });
    
    // Check if app is already installed
    window.addEventListener('appinstalled', () => {
        console.log('PWA was installed');
        hideInstallButtons();
        window.deferredPrompt = null;
        localStorage.setItem('pwa-install-dismissed', 'true');
    });
    
    // Check if running as installed app
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
        console.log('Running as installed PWA');
        document.addEventListener('DOMContentLoaded', hideInstallButtons);
    }
    
    // Hide loader function
    function hideLoader() {
        const loader = document.getElementById('app-loader');
        if (loader && !loader.classList.contains('hidden')) {
            loader.classList.add('hidden');
            setTimeout(() => {
                if (loader.parentNode) loader.remove();
            }, 300);
        }
    }
    
    // Hide loader when page is ready - multiple fallbacks
    window.addEventListener('load', () => setTimeout(hideLoader, 100));
    
    // Fallback: Hide after DOMContentLoaded + short delay
    document.addEventListener('DOMContentLoaded', () => setTimeout(hideLoader, 500));
    
    // Fallback: Maximum wait time of 3 seconds
    setTimeout(hideLoader, 3000);
    
    // Livewire fallback
    document.addEventListener('livewire:init', () => setTimeout(hideLoader, 100));
    document.addEventListener('livewire:navigated', hideLoader);
</script>