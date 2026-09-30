@extends('layouts.kt-public')
@section('title', 'Services | ' . config('clinic.name'))
@section('meta_description', 'Browse the dental services currently listed for online appointment requests at Pasigna Dental Clinic.')

@section('content')
<section class="pd-page-intro">
    <div class="pd-container">
        <span class="pd-eyebrow">Dental services</span>
        <h1 class="pd-title">Find a service and request a visit</h1>
        <p class="pd-lead">Only services currently added by the clinic appear below. Appointment requests are reviewed by staff before confirmation.</p>
    </div>
</section>
<section class="pd-section">
    <div class="pd-container">
        @if($services->isNotEmpty())
            <div class="pd-services-grid">
                @foreach($services as $service)
                    @php($bookUrl = route('public.booking.create', $service))
                    <article class="pd-service-card">
                        <h2>{{ $service->name }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($service->description ?: 'View the details for this service and available appointment options.', 150) }}</p>
                        <div class="pd-actions">
                            <a class="pd-text-link" href="{{ route('public.services.show', $service) }}">View details <span aria-hidden="true">→</span></a>
                            <a class="pd-text-link" href="{{ auth()->check() ? $bookUrl : route('userlogin', ['redirect' => $bookUrl]) }}">Request a visit <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="pd-empty">
                <h2>Our online service list is being prepared</h2>
                <p>We do not have a confirmed service catalog to show yet. Send an appointment inquiry and the clinic can respond through the contact form workflow.</p>
                <div class="pd-actions"><a class="pd-button" href="{{ route('public.contact') }}">Ask about an appointment</a></div>
            </div>
        @endif
    </div>
</section>
@endsection
