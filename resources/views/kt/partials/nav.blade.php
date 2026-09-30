@php($role = auth()->user()->role ?? 'user')
<header class="pd-header">
    <nav class="pd-nav pd-container" aria-label="Main navigation">
        <a class="pd-brand" href="{{ route('public.home') }}" aria-label="{{ config('clinic.name') }} home">
            <img src="{{ asset('images/PasignaAssets/logo.jpg') }}" width="58" height="58" alt="Pasigna Dental Clinic logo">
            <span>{{ config('clinic.name') }}<small>{{ config('clinic.city') }}</small></span>
        </a>
        <div class="pd-nav-links" id="pd-nav-links">
            <a href="{{ route('public.home') }}" @if(request()->routeIs('public.home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('public.about') }}" @if(request()->routeIs('public.about')) aria-current="page" @endif>About</a>
            <a href="{{ route('public.services.index') }}" @if(request()->routeIs('public.services.*')) aria-current="page" @endif>Services</a>
            <a href="{{ route('public.home') }}#gallery">Gallery</a>
            <a href="{{ route('public.contact') }}" @if(request()->routeIs('public.contact')) aria-current="page" @endif>Contact</a>
            @guest
                <a class="pd-mobile-account" href="{{ route('userlogin') }}">Patient login</a>
            @else
                <a class="pd-mobile-account" href="{{ in_array($role, ['admin', 'staff']) ? route('portal') : route('profile.show') }}">{{ in_array($role, ['admin', 'staff']) ? 'Staff portal' : 'My account' }}</a>
            @endguest
        </div>
        <div class="pd-nav-actions">
            @guest
                <a class="pd-nav-login" href="{{ route('userlogin') }}">Patient login</a>
            @else
                <a class="pd-nav-login" href="{{ in_array($role, ['admin', 'staff']) ? route('portal') : route('profile.show') }}">{{ in_array($role, ['admin', 'staff']) ? 'Staff portal' : 'My account' }}</a>
            @endguest
            <a class="pd-button pd-button-small pd-nav-book" href="{{ route('public.services.index') }}">Book an Appointment</a>
            <button class="pd-menu-toggle" id="pd-menu-toggle" type="button" aria-controls="pd-nav-links" aria-expanded="false" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
</header>
