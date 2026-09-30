<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', config('clinic.name') . ' | Patient Login')</title>

    {{-- ✅ PWA (Installable App) --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#563682">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Pasigna Dental Clinic">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    html,
    body {
        height: 100%;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    * {
        box-sizing: border-box;
    }
    </style>
</head>

<body>
    @yield('content')

    {{-- ✅ PWA Service Worker --}}
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            }).catch(() => {});
        });
    }
    </script>
</body>

</html>
