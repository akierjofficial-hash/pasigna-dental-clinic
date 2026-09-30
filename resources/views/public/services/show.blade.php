@extends('layouts.kt-public')
@section('title', $service->name . ' | ' . config('clinic.name'))
@section('meta_description', 'View ' . $service->name . ' and request an appointment with Pasigna Dental Clinic.')

@section('content')
@php($bookUrl = route('public.booking.create', $service))
<section class="pd-page-intro">
    <div class="pd-container">
        <a class="pd-text-link" href="{{ route('public.services.index') }}">← All services</a>
        <div style="margin-top:24px"><span class="pd-eyebrow">Service details</span><h1 class="pd-title">{{ $service->name }}</h1></div>
        <p class="pd-lead">{{ $service->description ?: 'Contact the clinic if you have questions about this service before booking.' }}</p>
    </div>
</section>
<section class="pd-section">
    <div class="pd-container pd-split">
        <div>
            <h2 class="pd-title">Plan your visit</h2>
            @if($service->duration_minutes)<p class="pd-copy"><strong>Listed duration:</strong> {{ $service->duration_minutes }} minutes</p>@endif
            @if($service->base_price !== null)<p class="pd-copy"><strong>Listed base price:</strong> ₱{{ number_format((float) $service->base_price, 2) }}</p>@endif
            <p class="pd-copy">Sign in to your patient account to request an appointment. Clinic staff review requests before confirmation.</p>
            <div class="pd-actions">
                <a class="pd-button" href="{{ auth()->check() ? $bookUrl : route('userlogin', ['redirect' => $bookUrl]) }}">Request an Appointment</a>
                <a class="pd-button pd-button-outline" href="{{ route('public.contact') }}">Ask a Question</a>
            </div>
        </div>
        <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/front2.jpg') }}" width="1320" height="1629" alt="Reception at Pasigna Dental Clinic" loading="lazy"></div>
    </div>
</section>
@endsection
