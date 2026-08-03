<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
    <div class="container-fluid px-lg-5">

        <!-- Logo / Brand Name -->
        <a class="navbar-brand brand-container" href="{{ route('Home') }}">
            <span class="brand-title">RiderFlow</span>
            <span class="brand-subtitle">Delivery Outsourcing</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('Home')) active @endif"
                        @if (request()->routeIs('Home')) aria-current="page" @endif
                        href="{{ route('Home') }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('about')) active @endif"
                        @if (request()->routeIs('about')) aria-current="page" @endif
                        href="{{ route('about') }}">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">SERVICES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">PORTFOLIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">BLOG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('contact')) active @endif"
                        @if (request()->routeIs('contact')) aria-current="page" @endif
                        href="{{ route('contact') }}">CONTACT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link search-icon" href="#" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>