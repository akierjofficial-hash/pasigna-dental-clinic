<footer class="pd-footer">
    <div class="pd-container pd-footer-grid">
        <div>
            <a class="pd-brand pd-footer-brand" href="{{ route('public.home') }}">
                <img src="{{ asset('images/PasignaAssets/logo.jpg') }}" width="64" height="64" alt="Pasigna Dental Clinic logo" loading="lazy">
                <span>{{ config('clinic.name') }}<small>{{ config('clinic.city') }}</small></span>
            </a>
            <p>{{ config('clinic.tagline') }}</p>
        </div>
        <div>
            <h2>Explore</h2>
            <a href="{{ route('public.about') }}">About the clinic</a>
            <a href="{{ route('public.services.index') }}">Services and booking</a>
            <a href="{{ route('public.home') }}#gallery">Gallery</a>
            <a href="{{ route('public.contact') }}">Contact</a>
        </div>
        <div>
            <h2>Visit and account</h2>
            <p>{{ config('clinic.city') }}</p>
            @if(config('clinic.address'))<p>{{ config('clinic.address') }}</p>@endif
            @if(config('clinic.hours'))<p>{{ config('clinic.hours') }}</p>@endif
            @if(config('clinic.phone'))<a href="tel:{{ preg_replace('/[^+\d]/', '', config('clinic.phone')) }}">{{ config('clinic.phone') }}</a>@endif
            @if(config('clinic.email'))<a href="mailto:{{ config('clinic.email') }}">{{ config('clinic.email') }}</a>@endif
            <a href="{{ route('userlogin') }}">Patient login</a>
        </div>
    </div>
    <div class="pd-container pd-footer-bottom">
        <span>&copy; {{ date('Y') }} {{ config('clinic.name') }}</span>
        <div><a href="{{ route('public.privacy') }}">Privacy Policy</a><a href="{{ route('public.terms') }}">Terms of Service</a></div>
    </div>
</footer>
