@extends('layouts.kt-public')
@section('title', 'Contact | ' . config('clinic.name'))
@section('meta_description', 'Send Pasigna Dental Clinic an inquiry about a visit or available dental services in Guihulngan, Negros Oriental.')

@section('content')
@php
    $user = auth()->user();
    $signedIn = auth()->check();
    $autoName = trim(old('name', $user->name ?? ''));
    $autoEmail = trim(old('email', $user->email ?? ''));
@endphp
<section class="pd-page-intro">
    <div class="pd-container">
        <span class="pd-eyebrow">Contact the clinic</span>
        <h1 class="pd-title">Let's start with your questions</h1>
        <p class="pd-lead">Ask about a visit, the online service list, or the next step for an appointment. Your message is sent to the clinic's contact inbox.</p>
    </div>
</section>
<section class="pd-section">
    <div class="pd-container pd-contact-grid">
        <aside class="pd-contact-info" aria-labelledby="contact-info-title">
            <span class="pd-eyebrow">Find us</span>
            <h2 id="contact-info-title">Clinic information</h2>
            <p><strong>Location:</strong> {{ config('clinic.city') }}</p>
            @if(config('clinic.address'))<p><strong>Address:</strong> {{ config('clinic.address') }}</p>@endif
            @if(config('clinic.hours'))<p><strong>Hours:</strong> {{ config('clinic.hours') }}</p>@endif
            @if(config('clinic.phone'))<p><strong>Phone:</strong> <a href="tel:{{ preg_replace('/[^+\d]/', '', config('clinic.phone')) }}">{{ config('clinic.phone') }}</a></p>@endif
            @if(config('clinic.email'))<p><strong>Email:</strong> <a href="mailto:{{ config('clinic.email') }}">{{ config('clinic.email') }}</a></p>@endif
            @if(config('clinic.facebook'))<p><strong>Facebook:</strong> <a href="{{ config('clinic.facebook') }}" rel="noopener noreferrer">Pasigna Dental Clinic</a></p>@endif
            <p>For online appointments, choose a listed service and sign in. Staff review requests before confirming them.</p>
            <a class="pd-text-link" href="{{ route('public.services.index') }}">Browse services <span aria-hidden="true">→</span></a>
        </aside>
        <div class="pd-contact-form">
            <h2>Send an inquiry</h2>
            <p>Provide an email address where the clinic can respond.</p>
            @if(session('contact_success'))<div class="pd-alert pd-alert-success" role="status">{{ session('contact_success') }}</div>@endif
            @if($errors->any())
                <div class="pd-alert" role="alert"><strong>Please review your message:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('public.contact.store') }}">
                @csrf
                <div class="pd-form-row">
                    <div class="pd-field">
                        <label for="pd-contact-name">Name</label>
                        @if($signedIn)
                            <input id="pd-contact-name" value="{{ $autoName }}" readonly aria-readonly="true">
                            <input type="hidden" name="name" value="{{ $autoName }}">
                        @else
                            <input id="pd-contact-name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="120">
                        @endif
                        @error('name')<span class="pd-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="pd-field">
                        <label for="pd-contact-email">Email</label>
                        @if($signedIn)
                            <input id="pd-contact-email" type="email" value="{{ $autoEmail }}" readonly aria-readonly="true">
                            <input type="hidden" name="email" value="{{ $autoEmail }}">
                        @else
                            <input id="pd-contact-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="190">
                        @endif
                        @error('email')<span class="pd-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="pd-field">
                    <label for="pd-contact-message">Message</label>
                    <textarea id="pd-contact-message" name="message" rows="6" required maxlength="5000" placeholder="How can we help?">{{ old('message') }}</textarea>
                    @error('message')<span class="pd-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="pd-button">Send message</button>
            </form>
        </div>
    </div>
    <div class="pd-container">
        <figure class="pd-location-map">
            <img src="{{ asset('images/PasignaAssets/map.png') }}" width="1486" height="772" alt="Map showing Pasigna Dental Clinic near Guihulngan City Auditorium" loading="lazy">
            <figcaption>Pasigna Dental Clinic in Guihulngan, Negros Oriental. Use the contact form above if you need help finding the clinic.</figcaption>
        </figure>
    </div>
</section>
@endsection
