@extends('layouts.kt-public')

@section('title', config('clinic.name'))

@section('content')
    @php
        $ktServices = $services ?? collect();
        $ktTestimonials = $testimonials ?? collect();
        $ktHeroStats = $heroStats ?? [];
        $ktTestimonialsTotal = $testimonialsTotal ?? $ktTestimonials->count();
    @endphp

    @include('kt.partials.hero', ['services' => $ktServices, 'heroStats' => $ktHeroStats])
    @include('kt.partials.services', ['services' => $ktServices])
    @include('kt.partials.about', ['heroStats' => $ktHeroStats])
    @include('kt.partials.testimonials', ['testimonials' => $ktTestimonials, 'testimonialsTotal' => $ktTestimonialsTotal])
    @include('kt.partials.booking', ['services' => $ktServices])
@endsection
