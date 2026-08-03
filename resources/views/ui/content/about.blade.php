@extends('ui.main')
@section('title', 'About us')
@section('content')
<header class="hero-banner">
    <div class="container">
        <h1 class="hero-title">About Us</h1>
        <p class="breadcrumb-text">
            <a href="{{ route('Home') }}">Home</a> &nbsp;&gt;&nbsp; About Us
        </p>
    </div>
</header>

<section class="about bg-light py-5">

    <div class="container px-4 px-lg-5">
        <div class="text-center mb-5">
            <h2 class="section-title mt-2 mb-3">
                About <span class="highlight-text">Us</span>
            </h2>
            <p class="section-description">
                Our team brings together data nerds and creative visionaries, united by an obsession with getting
                results. Get to know the people who will be working on your projects:
            </p>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="p-3 p-lg-5"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/01.jpg') }}" alt="..." />
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">For those about to rock...</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio veniam
                        sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis recusandae! Ratione
                        voluptatum molestiae adipisci, beatae obcaecati.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6">
                <div class="p-3 p-lg-5"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/02.jpg') }}" alt="..." />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">We salute you!</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio veniam
                        sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis recusandae! Ratione
                        voluptatum molestiae adipisci, beatae obcaecati.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-4 px-lg-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="p-3 p-lg-5"><img class="img-fluid rounded-circle" loading="lazy" decoding="async"
                        src="{{ asset('frontend/img/03.jpg') }}" alt="..." />
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="p-3 p-lg-5">
                    <h2 class="display-4">Let there be rock!</h2>
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
                            <i class="fa-solid fa-shapes"></i>
                        </div>
                        <h3 class="card-title-custom">Title Goes Here</h3>
                        <p class="card-text-custom">
                            Tailored to meet individual needs perfectly balanced.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="read-more-link text-pink">
                            Read more <i class="fa-solid fa-arrow-right-long fs-6"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-green">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h3 class="card-title-custom">Title Goes Here</h3>
                        <p class="card-text-custom">
                            Tailored to meet individual needs perfectly balanced.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="read-more-link text-green">
                            Read more <i class="fa-solid fa-arrow-right-long fs-6"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-blue">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                        <h3 class="card-title-custom">Title Goes Here</h3>
                        <p class="card-text-custom">
                            Tailored to meet individual needs perfectly balanced.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="read-more-link text-blue">
                            Read more <i class="fa-solid fa-arrow-right-long fs-6"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="service-card">
                    <div>
                        <div class="icon-box icon-orange">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="card-title-custom">Title Goes Here</h3>
                        <p class="card-text-custom">
                            Tailored to meet individual needs perfectly balanced.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="read-more-link text-orange">
                            Read more <i class="fa-solid fa-arrow-right-long fs-6"></i>
                        </a>
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