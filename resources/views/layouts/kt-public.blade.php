<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Get to know Pasigna Dental Clinic in Guihulngan, Negros Oriental. Explore the clinic, view available services, and request an appointment.')">
    <meta name="theme-color" content="#563682">
    <link rel="icon" type="image/png" href="{{ asset(config('clinic.favicon')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('clinic.favicon')) }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>@yield('title', config('clinic.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kt-public.css') }}?v={{ @filemtime(public_path('css/kt-public.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/pasigna-public.css') }}?v={{ @filemtime(public_path('css/pasigna-public.css')) }}">
    @stack('styles')
</head>
<body class="kt-site pd-site">
    <a class="pd-skip" href="#main-content">Skip to content</a>
    <div class="kt-loader" id="ktLoader" aria-hidden="true">
        <div class="kt-loader__inner">
            <img src="{{ asset(config('clinic.logo')) }}" alt="" class="kt-loader__logo" width="72" height="72" loading="eager">
            <div class="kt-loader__bar"><div class="kt-loader__progress"></div></div>
        </div>
    </div>

    @include('kt.partials.nav')
    <main id="main-content" class="kt-main">@yield('content')</main>
    @include('kt.partials.footer')

    <section class="kt-cookie" id="ktCookie" hidden aria-live="polite" aria-label="Cookie notice">
        <div class="kt-cookie__card">
            <p class="kt-cookie__title">Cookies Notice</p>
            <p class="kt-cookie__text">Essential cookies keep this website secure and functional.</p>
            <div class="kt-cookie__actions">
                <button type="button" class="kt-cookie__btn kt-cookie__btn--primary" id="ktCookieAccept">Accept</button>
            </div>
            <a href="{{ route('public.privacy') }}" class="kt-cookie__policy">Privacy Policy</a>
        </div>
    </section>

    <script src="{{ asset('js/kt-public.js') }}?v={{ @filemtime(public_path('js/kt-public.js')) }}"></script>
    <script src="{{ asset('js/pasigna-public.js') }}?v={{ @filemtime(public_path('js/pasigna-public.js')) }}"></script>
    @stack('scripts')
</body>
</html>
