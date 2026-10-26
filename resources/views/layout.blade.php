<!DOCTYPE html>
<html>
<head>
    <title>{{ config('clinic.name') }} System</title>

    {{-- ✅ PWA (Installable App) --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="{{ config('clinic.colors.deep') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('clinic.name') }}">
    <link rel="apple-touch-icon" href="{{ asset(config('clinic.favicon')) }}">
    <link rel="icon" type="image/png" href="{{ asset(config('clinic.favicon')) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="p-6">
    <div class="container mx-auto">
        @yield('content')
    </div>

{{-- ✅ PWA Service Worker --}}
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {});
        });
    }
</script>
</body>
</html>
