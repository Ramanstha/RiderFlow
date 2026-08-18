@extends('ui.main')
@section('title', 'Services')
@section('content')
<header class="hero-banner">
    <div class="container">
        <h1 class="hero-title">Services</h1>
        <p class="breadcrumb-text">
            <a href="{{ route('Home') }}">Home</a> &nbsp;&gt;&nbsp; Services
        </p>
    </div>
</header>

<section class="services-section bg-white">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">
                Our <span class="highlight-text">Services</span>
            </h2>
            <p class="section-subtitle">
                Our mission is to drive progress and enhance the lives of our customers by delivering superior
                products and services that exceed expectations.
            </p>
        </div>

        <!-- Services Cards Grid -->
        <div class="row g-4 mb-5">

            <!-- Card 1 -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-pink">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <h3 class="card-title-custom">Subscription Basis</h3>
                        <p class="card-text-custom">
                            Empower your business with reliable subscription-based delivery riders. We provide trained
                            professionals, flexible plans, and seamless rider management to ensure efficient and timely
                            deliveries.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-green">
                            <i class="fa-solid fa-shapes"></i>
                        </div>
                        <h3 class="card-title-custom">Parcel Basis</h3>
                        <p class="card-text-custom">
                            Our parcel-based delivery service provides professional riders for fast, secure, and
                            reliable deliveries. Pay only per parcel while we ensure efficient and timely service for
                            your business.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-orange">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="card-title-custom">Last Mile</h3>
                        <p class="card-text-custom">
                            Deliver the final mile with confidence. Our last-mile delivery service provides reliable
                            riders and efficient logistics to ensure every order reaches your customers quickly, safely,
                            and on time.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom CTA Button -->
        <div class="text-center">
            <a href="#" class="btn btn-hire">
                Hire Us Today <i class="fa-solid fa-arrow-right-long ms-1"></i>
            </a>
        </div>
    </div>
</section>

<section class="team-section bg-light py-5">
    <div class="container">

        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="section-subtitle">MEET THE RIDERS</span>
            <h2 class="section-title mt-2 mb-3">
                The Dedication Behind <span class="highlight-text">Your Success</span>
            </h2>
            <p class="section-description">
                Our team brings together data nerds and creative visionaries, united by an obsession with getting
                results. Get to know the people who will be working on your projects:
            </p>
        </div>

        <!-- Team Members (Owl Carousel: 1 / 2 / 3 / 4 slides per breakpoint) -->
        @if($riders->isNotEmpty())
        <div class="owl-carousel owl-theme riders-carousel">

            @foreach($riders as $rider)
            <div class="team-card text-center">
                <div class="team-img-wrapper">
                    <img loading="lazy" decoding="async"
                        src="{{ $rider->photo ? asset('storage/' . $rider->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rider->name) }}"
                        alt="{{ $rider->name }}">
                </div>
                <h3 class="member-name">{{ $rider->name }}</h3>
                <p class="member-role">Rider</p>
                <button class="btn btn-meet">MEET {{ $rider->name }}</button>
            </div>
            @endforeach

        </div>
        @endif
    </div>
</section>
@endsection