@extends('layouts.kt-public')

@section('title', config('clinic.name') . ' | Healthy Smiles • Brighter Tomorrows')

@section('content')
<section class="pd-hero" aria-labelledby="home-title">
    <div class="pd-container pd-hero-grid">
        <div class="pd-hero-copy">
            <span class="pd-eyebrow">Welcome to Pasigna Dental Clinic</span>
            <h1 id="home-title">Healthy Smiles <span>• Brighter Tomorrows</span></h1>
            <p class="pd-lead">A welcoming place to explore your dental care options in Guihulngan, Negros Oriental. Get to know the clinic and request a visit when you are ready.</p>
            <div class="pd-actions">
                <a class="pd-button" href="{{ route('public.services.index') }}">Book an Appointment</a>
                <a class="pd-button pd-button-outline" href="#gallery">Explore Our Clinic</a>
            </div>
        </div>
        <div class="pd-hero-visual">
            <img src="{{ asset('images/PasignaAssets/front.jpg') }}" width="4096" height="3072" alt="Reception and entrance area at Pasigna Dental Clinic" fetchpriority="high">
            <div class="pd-hero-note">Here in Guihulngan <small>Negros Oriental</small></div>
        </div>
    </div>
</section>

<section class="pd-section" id="about" aria-labelledby="about-title">
    <div class="pd-container pd-split">
        <div class="pd-photo pd-photo-team">
            <img src="{{ asset('images/PasignaAssets/pasigna_team.jpg') }}" width="1125" height="1500" alt="A group at Pasigna Dental Clinic" loading="lazy">
        </div>
        <div>
            <span class="pd-eyebrow">About the clinic</span>
            <h2 class="pd-title" id="about-title">A familiar place for your dental visits</h2>
            <p class="pd-copy">Pasigna Dental Clinic welcomes patients in Guihulngan, Negros Oriental. From your first inquiry to your visit, the team aims to make each step clear and approachable.</p>
            <p class="pd-copy">Take a look inside the clinic, browse the services that are currently available online, and reach out with questions before requesting an appointment.</p>
            <div class="pd-actions"><a class="pd-text-link" href="{{ route('public.about') }}">More about the clinic <span aria-hidden="true">→</span></a></div>
        </div>
    </div>
</section>

<section class="pd-section pd-section-soft" id="services" aria-labelledby="services-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Dental services</span>
            <h2 class="pd-title" id="services-title">Explore care available at Pasigna</h2>
            <p class="pd-lead">Services listed here come directly from the clinic's current appointment catalog.</p>
        </div>
        @if($services->isNotEmpty())
            <div class="pd-services-grid">
                @foreach($services->take(6) as $service)
                    <article class="pd-service-card">
                        <h3>{{ $service->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->description ?: 'View service details and appointment options.', 120) }}</p>
                        <a class="pd-text-link" href="{{ route('public.services.show', $service) }}">View service <span aria-hidden="true">→</span></a>
                    </article>
                @endforeach
            </div>
            <div class="pd-actions"><a class="pd-button pd-button-outline" href="{{ route('public.services.index') }}">All services</a></div>
        @else
            <div class="pd-empty">
                <h3>Services are being prepared</h3>
                <p>The online service list is not available yet. Send the clinic an inquiry to ask about an appointment.</p>
                <div class="pd-actions"><a class="pd-button" href="{{ route('public.contact') }}">Ask about an appointment</a></div>
            </div>
        @endif
    </div>
</section>

<section class="pd-section" id="gallery" aria-labelledby="gallery-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Inside our clinic</span>
            <h2 class="pd-title" id="gallery-title">See the spaces before your visit</h2>
            <p class="pd-lead">A glimpse of the reception and treatment areas at Pasigna Dental Clinic.</p>
        </div>
        <div class="pd-gallery-grid">
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/front2.jpg') }}" width="1320" height="1629" alt="Reception desk and seating at Pasigna Dental Clinic" loading="lazy"></div>
                <figcaption>The reception area</figcaption>
            </figure>
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/busywork.jpg') }}" width="4096" height="3072" alt="Treatment room with dental chairs and staff at work" loading="lazy"></div>
                <figcaption>The treatment space</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="pd-section pd-section-soft" aria-labelledby="care-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Care in action</span>
            <h2 class="pd-title" id="care-title">A closer look at clinic life</h2>
            <p class="pd-lead">These moments show the team and treatment setting during everyday care.</p>
        </div>
        <div class="pd-care-grid">
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/ongoingwork3.jpg') }}" width="4096" height="3072" alt="A dental team member attending to a patient in the treatment room" loading="lazy"></div>
                <figcaption>Care in the treatment room</figcaption>
            </figure>
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/ongoingwork.jpg') }}" width="4096" height="3072" alt="Dental staff working together with a patient" loading="lazy"></div>
                <figcaption>Teamwork during a visit</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="pd-section" aria-labelledby="moments-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Patient moments</span>
            <h2 class="pd-title" id="moments-title">Faces from the clinic</h2>
            <p class="pd-lead">A few shared moments at Pasigna Dental Clinic.</p>
        </div>
        <div class="pd-moments-grid">
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/satisfied_patients.jpg') }}" width="1536" height="2048" alt="A patient and a clinic team member posing together" loading="lazy"></div>
                <figcaption>A moment after a clinic visit</figcaption>
            </figure>
            <figure>
                <div class="pd-photo"><img src="{{ asset('images/PasignaAssets/satisfied_patients2.jpg') }}" width="1536" height="2048" alt="A patient smiling beside a clinic team member" loading="lazy"></div>
                <figcaption>A smile shared at the clinic</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="pd-section pd-section-soft" id="your-visit" aria-labelledby="visit-title">
    <div class="pd-container">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Your visit</span>
            <h2 class="pd-title" id="visit-title">How appointment requests work</h2>
            <p class="pd-lead">The online form helps you request a visit. Clinic staff review requests before an appointment is confirmed.</p>
        </div>
        <div class="pd-steps">
            <article class="pd-step"><span>01</span><h3>Choose a service</h3><p>Browse the available service list and sign in to your patient account to begin a request.</p></article>
            <article class="pd-step"><span>02</span><h3>Send your request</h3><p>Provide the details requested by the booking form. The request remains pending while staff review it.</p></article>
            <article class="pd-step"><span>03</span><h3>Check for confirmation</h3><p>Review the status in your account. Visit the clinic after your appointment is approved.</p></article>
        </div>
    </div>
</section>

<section class="pd-section" aria-labelledby="faq-title">
    <div class="pd-container pd-faq">
        <div class="pd-section-head">
            <span class="pd-eyebrow">Frequently asked questions</span>
            <h2 class="pd-title" id="faq-title">Before you book</h2>
        </div>
        <details><summary>Do I need an account to request an appointment?</summary><p>Yes. Choose a listed service and sign in to your patient account to complete the online booking form.</p></details>
        <details><summary>Is my requested time confirmed right away?</summary><p>No. New requests are reviewed by clinic staff. Check your account for the request status before visiting.</p></details>
        <details><summary>What if I cannot find a service online?</summary><p>The service catalog may still be getting set up. You can use the <a href="{{ route('public.contact') }}">contact form</a> to ask the clinic about your visit.</p></details>
        <details><summary>Where is the clinic?</summary><p>Pasigna Dental Clinic is in {{ config('clinic.city') }}. @if(config('clinic.address')){{ config('clinic.address') }}@else Ask the clinic for the full address before your visit.@endif</p></details>
    </div>
</section>

<section class="pd-final" aria-labelledby="final-title">
    <div class="pd-container">
        <div><h2 id="final-title">Ready to get in touch?</h2><p>Explore listed services and request a visit, or send an inquiry if you need help choosing the next step.</p></div>
        <div class="pd-actions"><a class="pd-button pd-button-light" href="{{ route('public.services.index') }}">Book an Appointment</a><a class="pd-button pd-button-outline" href="{{ route('public.contact') }}">Contact the Clinic</a></div>
    </div>
</section>
@endsection
