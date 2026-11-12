@extends('layouts.kt-public')
@section('title', 'About | ' . config('clinic.name'))
@section('meta_description', 'Get to know Pasigna Dental Clinic in Guihulngan, Negros Oriental and see the people and spaces behind your visit.')

@section('content')
<section class="pd-page-intro">
    <div class="pd-container">
        <span class="pd-eyebrow">About us</span>
        <h1 class="pd-title">A welcoming clinic in Guihulngan</h1>
        <p class="pd-lead">Pasigna Dental Clinic is a place to ask questions, explore your care options, and plan a dental visit with the team.</p>
    </div>
</section>
<section class="pd-section">
    <div class="pd-container pd-split">
        <div class="pd-photo pd-photo-team"><img src="{{ asset('images/PasignaAssets/pasigna_team.jpg') }}" width="1125" height="1500" alt="A group at Pasigna Dental Clinic" loading="lazy"></div>
        <div>
            <span class="pd-eyebrow">Get to know us</span>
            <h2 class="pd-title">Care starts with a conversation</h2>
            <p class="pd-copy">A clinic visit is easier when you know what to expect. Our website lets you look around, check the services currently listed for booking, and send an inquiry before you come in.</p>
            <p class="pd-copy">You will find Pasigna Dental Clinic in {{ config('clinic.city') }}. If you are planning a visit, use the contact form for questions or browse available appointment options.</p>
            <div class="pd-actions"><a class="pd-button" href="{{ route('public.services.index') }}">Explore Services</a><a class="pd-button pd-button-outline" href="{{ route('public.contact') }}">Ask a Question</a></div>
        </div>
    </div>
</section>
<section class="pd-section pd-section-soft" aria-labelledby="clinic-details-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Plan your visit</span>
            <h2 class="pd-title" id="clinic-details-title">Clinic details</h2>
            <p class="pd-lead">Find us, call the clinic, or visit our Facebook page before coming in.</p>
        </div>
        <dl class="pd-clinic-details">
            <div>
                <dt>Address</dt>
                <dd>{{ config('clinic.address') ?: config('clinic.city') }}</dd>
            </div>
            @if(config('clinic.hours'))
                <div>
                    <dt>Opening hours</dt>
                    <dd>{{ config('clinic.hours') }}</dd>
                </div>
            @endif
            @if(config('clinic.phone'))
                <div>
                    <dt>Phone number</dt>
                    <dd><a href="tel:{{ preg_replace('/[^+\d]/', '', config('clinic.phone')) }}">{{ config('clinic.phone') }}</a></dd>
                </div>
            @endif
            @if(config('clinic.facebook'))
                <div>
                    <dt>Facebook</dt>
                    <dd><a href="{{ config('clinic.facebook') }}" rel="noopener noreferrer">Pasigna Dental Clinic on Facebook</a></dd>
                </div>
            @endif
            @if(config('clinic.email'))
                <div>
                    <dt>Email</dt>
                    <dd><a href="mailto:{{ config('clinic.email') }}">{{ config('clinic.email') }}</a></dd>
                </div>
            @endif
        </dl>
        <div class="pd-actions"><a class="pd-text-link" href="{{ route('public.contact') }}">View the location map and contact form <span aria-hidden="true">→</span></a></div>
    </div>
</section>
<section class="pd-section">
    <div class="pd-container">
        <div class="pd-section-head"><span class="pd-eyebrow">A look inside</span><h2 class="pd-title">Spaces for your visit</h2></div>
        <div class="pd-gallery-grid">
            <figure><div class="pd-photo"><img src="{{ asset('images/PasignaAssets/front2.jpg') }}" width="1320" height="1629" alt="Reception and seating area at Pasigna Dental Clinic" loading="lazy"></div><figcaption>Reception and waiting area</figcaption></figure>
            <figure><div class="pd-photo"><img src="{{ asset('images/PasignaAssets/busywork.jpg') }}" width="4096" height="3072" alt="Dental treatment room at Pasigna Dental Clinic" loading="lazy"></div><figcaption>The treatment space</figcaption></figure>
        </div>
    </div>
</section>
@endsection
