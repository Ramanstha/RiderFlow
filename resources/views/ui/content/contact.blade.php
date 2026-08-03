@extends('ui.main')
@section('title', 'Contact us')
@section('content')
<!-- Hero Header Section -->
<header class="hero-banner">
    <div class="container">
        <h1 class="hero-title">Contact Us</h1>
        <p class="breadcrumb-text">
            <a href="{{ route('Home') }}">Home</a> &nbsp;&gt;&nbsp; Contact
        </p>
    </div>
</header>

<!-- Main Content Section -->
<section class="contact-section py-4 py-md-5 my-md-4">
    <div class="container">
        <div class="row g-4 g-lg-5">

            <!-- Left Column: Contact Form -->
            <div class="col-lg-7">
                <div class="subtitle mb-2">
                    <i class="fa-solid fa-user-gear"></i> SEND US EMAIL
                </div>
                <h2 class="section-title">Feel free to write</h2>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input type="text" name="name" value="{{ old('name') }}" autocomplete="name"
                                class="form-control form-control-custom @error('name') is-invalid @enderror"
                                placeholder="Enter Name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                                class="form-control form-control-custom @error('email') is-invalid @enderror"
                                placeholder="Enter Email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="form-control form-control-custom @error('subject') is-invalid @enderror"
                                placeholder="Enter Subject">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                                inputmode="tel"
                                class="form-control form-control-custom @error('phone') is-invalid @enderror"
                                placeholder="Enter Phone">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <textarea class="form-control form-control-custom @error('message') is-invalid @enderror" name="message" rows="5"
                                placeholder="Enter Message" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-3 mt-md-4 d-flex flex-wrap gap-2 form-actions">
                            <button type="submit" class="btn btn-red">SEND MESSAGE</button>
                            <button type="reset" class="btn btn-red">RESET</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Contact Details -->
            <div class="col-lg-5">
                <div class="subtitle mb-2">
                    <i class="fa-solid fa-user-gear"></i> NEED ANY HELP?
                </div>
                <h2 class="section-title">Get in touch with us</h2>

                <p class="text-muted mb-4 info-intro">
                    Lorem ipsum is simply dummy text available available dolor sit amet consectetur nullam adipiscing
                    elit sed
                    do eiusmod tempor incididunt simply dolore magna.
                </p>

                <!-- Info Box 1 -->
                 @if($contact)
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <div class="info-label">Have any question?</div>
                        <p class="info-text">{{$contact->phone}}</p>
                    </div>
                </div>

                <!-- Info Box 2 -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <div>
                        <div class="info-label">Write email</div>
                        <p class="info-text">{{$contact->email}}</p>
                    </div>
                </div>

                <!-- Info Box 3 -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <div class="info-label">Visit Anytime</div>
                        <p class="info-text">{{$contact->address}}</p>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>

<!-- Google Map Section -->
<div class="map-container">
    <iframe
        src="{{$contact->map}}"
        title="RiderFlow office location on Google Maps" width="100%" height="100%" style="border:0;" allowfullscreen=""
        loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
</div>
@endsection