@php($role = auth()->user()->role ?? 'user')
<header class="pd-header">
    <nav class="pd-nav pd-container" aria-label="Main navigation">
        <a class="pd-brand" href="{{ route('public.home') }}" aria-label="{{ config('clinic.name') }} home">
            <img src="{{ asset(config('clinic.logo')) }}" width="58" height="58" alt="Pasigna Dental Clinic logo">
            <span>{{ config('clinic.name') }}<small>{{ config('clinic.city') }}</small></span>
        </a>
        <div class="pd-nav-links" id="pd-nav-links">
            <a href="{{ route('public.home') }}" data-pd-nav-home @if(request()->routeIs('public.home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('public.about') }}" @if(request()->routeIs('public.about')) aria-current="page" @endif>About</a>
            <a href="{{ route('public.services.index') }}" @if(request()->routeIs('public.services.*')) aria-current="page" @endif>Services</a>
            <a href="{{ route('public.home') }}#gallery" data-pd-nav-gallery>Gallery</a>
            <a href="{{ route('public.contact') }}" @if(request()->routeIs('public.contact')) aria-current="page" @endif>Contact</a>
            @guest
                <a class="pd-mobile-account" href="{{ route('userlogin') }}">Patient login</a>
            @else
                <a class="pd-mobile-account" href="{{ in_array($role, ['admin', 'staff']) ? route('portal') : route('profile.show') }}">{{ in_array($role, ['admin', 'staff']) ? 'Staff portal' : 'My account' }}</a>
            @endguest
        </div>
        <div class="pd-nav-actions">
            @guest
                <a class="pd-nav-login" href="{{ route('userlogin') }}" aria-label="Patient login"><span class="pd-nav-login-full">Patient login</span><span class="pd-nav-login-short" aria-hidden="true">Log in</span></a>
            @else
                <div class="kt-nav__profile pd-profile">
                    <button class="kt-nav__avatar-btn" id="ktProfileToggle" type="button" aria-controls="ktProfileMenu" aria-expanded="false" aria-haspopup="true" aria-label="Open profile menu">
                        <span class="kt-nav__avatar" aria-hidden="true">{{ strtoupper(substr(trim(auth()->user()->name ?: 'U'), 0, 1)) }}</span>
                    </button>
                    <div class="kt-nav__dropdown" id="ktProfileMenu" aria-label="Account options">
                        <div class="kt-nav__dropdown-head">
                            <div class="kt-nav__dropdown-name">{{ auth()->user()->name }}</div>
                            <div class="kt-nav__dropdown-email">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="kt-nav__dropdown-sep"></div>
                        @if($role === 'user')
                            <a class="kt-nav__dropdown-link" href="{{ route('profile.show') }}" @if(request()->routeIs('profile.show')) aria-current="page" @endif>My Profile</a>
                            <a class="kt-nav__dropdown-link" href="{{ route('public.installments.index') }}">My Installment Plans</a>
                        @else
                            <a class="kt-nav__dropdown-link" href="{{ route('portal') }}">Staff portal</a>
                        @endif
                        <div class="kt-nav__dropdown-sep"></div>
                        <form class="kt-nav__dropdown-form" method="POST" action="{{ route('userlogout') }}">
                            @csrf
                            <button class="kt-nav__dropdown-link kt-nav__dropdown-link--button" type="submit">Log out</button>
                        </form>
                    </div>
                </div>
            @endguest
            <a class="pd-button pd-button-small pd-nav-book" href="{{ route('public.services.index') }}">Book an Appointment</a>
            <button class="pd-menu-toggle" id="pd-menu-toggle" type="button" aria-controls="pd-nav-links" aria-expanded="false" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
</header>
