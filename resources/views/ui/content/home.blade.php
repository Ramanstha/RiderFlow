@extends('ui.main')
@section('title', 'Home')
@section('content')

<div class="hero-section">
    <!-- Background Video-->
    @if($banner?->video)
    <video class="bg-video" src="{{ asset('storage/' . $banner->video) }}" playsinline="playsinline" autoplay="autoplay"
        muted="muted" loop="loop" preload="auto" poster="{{ asset('frontend/img/bg-mobile-fallback.jpg') }}"></video>
    @endif
    <!-- Masthead-->
    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="container-fluid px-4 px-lg-0 mt-5">
                <h1 class="fst-italic lh-1 mb-4">{{ $banner?->title }}</h1>
                <p class="mb-5">{{ $banner?->subtitle }}</p>
            </div>
        </div>
    </div>
    <!-- Social Icons-->
    <!-- For more icon options, visit https://fontawesome.com/icons?d=gallery&p=2&s=brands-->
    <div class="social-icons">
        @if($contact)
        <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
            @if($contact->tweeter)
            <a class="btn btn-dark m-3" href="{{ $contact->tweeter }}"><i class="fab fa-twitter"></i></a>
            @endif
            @if($contact->facebook)
            <a class="btn btn-dark m-3" href="{{ $contact->facebook }}"><i class="fab fa-facebook-f"></i></a>
            @endif
            @if($contact->instagram)
            <a class="btn btn-dark m-3" href="{{ $contact->instagram }}"><i class="fab fa-instagram"></i></a>
            @endif
        </div>
        @endif
    </div>
</div>

<section class="about bg-light py-5">

    <div class="container px-4 px-lg-5">
        <div class="text-center mb-5">
            <h2 class="section-title mt-2 mb-3">
                About <span class="highlight-text">Us</span>
            </h2>
            <p class="section-description">
                We connect businesses with skilled delivery riders to meet their logistics and last-mile delivery needs.
                Our commitment to reliability, flexibility, and quality helps your business deliver with confidence.
            </p>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="p-3"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/about1.png') }}" alt="..." /></div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">Our Goal...</h2>
                    <p>Our goal is to provide businesses with reliable and professional rider outsourcing solutions that
                        improve delivery efficiency. We strive to build long-term partnerships through dependable
                        service, skilled riders, and consistent operational excellence. By supporting businesses of all
                        sizes, we aim to make last-mile delivery faster, safer, and more efficient.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6">
                <div class="p-3"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/about2.png') }}" alt="..." /></div>
            </div>
            <div class="col-lg-6">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">Our Mission !!!</h2>
                    <p>Our mission is to connect businesses with trained and dedicated delivery riders who ensure timely
                        and secure deliveries. We are committed to maintaining high standards of professionalism,
                        customer satisfaction, and operational reliability. Through flexible workforce solutions and
                        continuous improvement, we help our clients grow while delivering exceptional service every day.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="p-3"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/03.jpg') }}" alt="..." /></div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">Let there be rock !!!</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio veniam
                        sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis recusandae! Ratione
                        voluptatum molestiae adipisci, beatae obcaecati.</p>
                </div>
            </div>
        </div>
    </div>
</section>
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
            <div class="col-12 col-sm-6 col-lg-3">
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
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-green">
                            <i class="fa-solid fa-box-open"></i>
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

            <!-- Card 3 -->
            <div class="col-12 col-sm-6 col-lg-3">
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

            <!-- Card 4 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-blue">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                        <h3 class="card-title-custom">Temporary</h3>
                        <p class="card-text-custom">
                            Flexible, reliable rider outsourcing for seasonal peaks, promotions, and short-term delivery
                            needs. Get verified and trained riders on demand—without the commitment of permanent hiring.
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
                <!-- <button class="btn btn-meet">MEET {{ $rider->name }}</button> -->
            </div>
            @endforeach

        </div>
        @endif
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section bg-white">
    <div class="container px-4 px-lg-5">

        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="section-subtitle">TESTIMONIALS</span>
            <h2 class="section-title mt-2 mb-3">
                What Our <span class="highlight-text">Clients Say</span>
            </h2>
            <p class="section-description">
                Restaurants, cloud kitchens and retailers rely on our riders every day. Here is what a few of
                them have to say about working with RiderFlow:
            </p>
        </div>

        <!-- Testimonial Cards (Owl Carousel: 1 / 2 / 3 slides per breakpoint) -->
        @if($testimonial->isNotEmpty())
        <div class="owl-carousel owl-theme testimonials-carousel">

            @foreach($testimonial as $testimonial)
            <figure class="testimonial-card">
                <div class="avatar-wrapper">
                    <img loading="lazy" decoding="async" width="90" height="90"
                        src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial->name) }}"
                        alt="" class="avatar-img">
                </div>
                <blockquote class="quote-text">
                    <i class="fa-solid fa-quote-left quote-icon" aria-hidden="true"></i>
                    {{$testimonial->message}}
                    <i class="fa-solid fa-quote-right quote-icon" aria-hidden="true"></i>
                </blockquote>
                <figcaption class="author-info">
                    <span class="author-name text-capitalize">{{$testimonial->name}}</span>
                    <span class="author-role text-capitalize">{{$testimonial->designation}}</span>
                </figcaption>
            </figure>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection