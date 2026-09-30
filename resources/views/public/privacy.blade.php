@extends('layouts.kt-public')
@section('title', 'Privacy Policy | ' . config('clinic.name'))
@section('content')
<section class="pd-page-intro"><div class="pd-container"><span class="pd-eyebrow">Website information</span><h1 class="pd-title">Privacy Policy</h1><p class="pd-lead">How this website uses the information you choose to submit.</p></div></section>
<section class="pd-section"><div class="pd-container pd-legal-stack">
    <article><h2>Information you provide</h2><p>The contact form collects your name, email address, and message. If you create an account or request an appointment, the website also stores the account and booking details you submit.</p></article>
    <article><h2>How it is used</h2><p>Clinic staff use submitted information to review appointment requests, manage visits, and respond to inquiries. The website also records the time and status of requests so you can review them in your account.</p></article>
    <article><h2>Essential cookies</h2><p>Session and security cookies support login and form protection. Your choice on the cookie notice is stored in your browser.</p></article>
    <article><h2>Questions about your information</h2><p>If you have a question about information you submitted through this site, <a href="{{ route('public.contact') }}">send the clinic a message</a>.</p></article>
</div></section>
@endsection
