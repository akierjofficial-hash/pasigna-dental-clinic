@extends('layouts.kt-public')
@section('title', 'Terms of Service | ' . config('clinic.name'))
@section('content')
<section class="pd-page-intro"><div class="pd-container"><span class="pd-eyebrow">Website information</span><h1 class="pd-title">Terms of Service</h1><p class="pd-lead">Information about using the online clinic website and appointment request tools.</p></div></section>
<section class="pd-section"><div class="pd-container pd-legal-stack">
    <article><h2>Using this website</h2><p>Use the contact, account, and booking forms with accurate information so the clinic can review your requests and respond.</p></article>
    <article><h2>Appointment requests</h2><p>Submitting a booking form creates a request. It is reviewed by clinic staff and is not confirmed immediately. Check the status in your account before visiting.</p></article>
    <article><h2>Service information</h2><p>The service list reflects what has been entered into the clinic's online catalog. The website does not replace a discussion with the clinic about your care.</p></article>
    <article><h2>Questions</h2><p>If you need help with a request or information on this website, <a href="{{ route('public.contact') }}">contact the clinic</a>.</p></article>
</div></section>
@endsection
