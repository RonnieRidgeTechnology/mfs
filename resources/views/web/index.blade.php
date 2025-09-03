@extends('layouts.website')
@section('meta_title', $home->meta_title ?? '')
@section('meta_description', $home->meta_desc ?? '')
@section('meta_keywords', $home->meta_keyword ?? '')
@section('content')

<div class="hero-wrapper" id="heroWrapper"
style="background-image: url('{{ asset('assets/web/images/hero_bg_1_1.jpg') }}');">
@include('layouts.mobile_nav')

        <!-- Hero Content Center -->
        <div class="hero-center-content">
            <h1>{{ isset($home->main_title) ? $home->main_title : '' }}</h1>
            <p>
                {{ isset($home->main_desc) ? $home->main_desc : '' }}
            </p>
            <a href="{{route('contact.public')}}" class="hero-cta-btn">Join Now</a>
        </div>

        <!-- Dots -->
        <div class="main-hero-dots"></div>


    </div>
    <!-- header-start -->
    <div class="header-main">
        @include('layouts.navbar')
        <div class="dots"></div>
    </div>
    <!-- homepage-seaction2-start -->
    <div class="homepage-seaction4-main">
        <div class="homepage-seaction4-left">
            <div class="homepage-seaction4-images">
                <div class="homepage-seaction4-img1">
                    <img src="{{ isset($home->section1_image1) ? asset($home->section1_image1) : '' }}" alt="" />
                </div>
                <div class="homepage-seaction4-img2">
                    <div class="homepage-seaction4-img4">
                        <img src="{{ isset($home->section1_image2) ? asset($home->section1_image2) : '' }}" alt="" />
                    </div>
                    <div class="homepage-seaction4-img4">
                        <img src="{{ isset($home->section1_image3) ? asset($home->section1_image3) : '' }}" alt="" />
                    </div>
                </div>
            </div>
        </div>
        <div class="homepage-seaction4-right">
            <div class="homepage-seaction4-context">
                <h6>{{ isset($home->section1_main_title) ? $home->section1_main_title : '' }}</h6>
                <h1>{{ isset($home->section1_title) ? $home->section1_title : '' }}</h1>
                <p>
                    {{ isset($home->section1_desc) ? $home->section1_desc : '' }}
                </p>
            </div>
            <div class="homepage-seaction4-points-main">
                @if(isset($home->section1_points) && !empty($home->section1_points) && is_array($home->section1_points))
                    @foreach($home->section1_points as $point)
                        <div class="homepage-seaction4-point1">
                            <div class="homepage-seaction4-points-icons">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="homepage-seaction4-points-text">
                                <p>{{ isset($point) ? $point : '' }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="homepage-seaction5-btn">
                <a href="{{ route('paymentsucces.public') }}">read more</a>
            </div>
        </div>
    </div>
    <div class="new-seaction-maindiv1" style="margin-top: 40px;">
        <div class="new-seaction-maindiv1-context">
            <h1>Islam Pillars</h1>
            <p>Five Pillars Of Islam</p>
        </div>
        <div class="new-seaction-divmain">
            @if(isset($pillar) && is_iterable($pillar))
                @foreach($pillar as $item)
                    <div class="new-seaction-card1">
                        <div class="new-seaction-card1-img">
                            <img src="{{ isset($item->image) ? asset($item->image) : '' }}"
                                alt="{{ isset($item->name) ? $item->name : '' }}" />
                        </div>
                        <div class="new-seaction-context">
                            <h1>{{ isset($item->name) ? $item->name : '' }}</h1>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- homepage-seaction5-start -->
    <div class="homepage-seaction5-main">
        <div class="homepage-seaction5-left">
            <div class="homepage-seaction5-imges">
                <div class="border"></div>
                <div class="homepage-seaction5-img">
                    <img src="{{ isset($home->section3_image) ? asset($home->section3_image) : '' }}" alt="" />
                </div>
            </div>
        </div>
        <div class="homepage-seaction5-right">
            <div class="homepage-seaction5-context">
                <h6>{{ isset($home->section3_main_title) ? $home->section3_main_title : '' }}</h6>
                <h1>{{ isset($home->section3_title) ? $home->section3_title : '' }}</h1>
                <p>
                    {{ isset($home->section3_desc) ? $home->section3_desc : '' }}
                </p>
                <a href="{{ route('council.public') }}">Read more</a>
            </div>
        </div>
    </div>
    <!-- homepage-seaction6-start -->
    <!-- homepage-seaction4-start -->
    <div class="homepage-seaction4-main-div">
        <div class="homepage-seaction2-main">
            <div class="homepage-seaction2-context">
                <h6>بِسْمِ ٱللّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيم</h6>
                <h1>{{ isset($home->footer_main_title) ? $home->footer_main_title : '' }}</h1>
                <p>
                    {{ isset($home->footer_main_desc) ? $home->footer_main_desc : '' }}
                </p>
                <div class="homepage-seaction2-btn">
                    <div class="pricing-btn2">
                        <a href="{{ route('council.public') }}">Read More</a>
                    </div>
                    <div class="pricing-btn2">
                        <a href="{{ route('contact.public') }}">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="homepage-seaction2-images">
                <img src="{{ asset('assets/web/images/10.png') }}" alt="" />
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // === HERO BACKGROUND SLIDER ===
        // Use Laravel's asset() helper to get the correct image URLs
        const heroImages = [
            "{{ $home->main_image1 ? asset($home->main_image1) : asset('assets/web/images/hero_bg_1_1.jpg') }}",
            "{{ $home->main_image2 ? asset($home->main_image2) : asset('assets/web/images/hero_bg_1_3.jpg') }}"
        ];
        const heroWrapper = document.getElementById("heroWrapper");
        const dotsContainer = document.querySelector(".main-hero-dots");

        let heroIndex = 0;
        let heroSliderInterval;

        function createHeroDots() {
            dotsContainer.innerHTML = "";
            heroImages.forEach((_, index) => {
                const dot = document.createElement("span");
                dot.addEventListener("click", () => {
                    showHeroImage(index);
                    resetHeroAutoSlide();
                });
                dotsContainer.appendChild(dot);
            });
        }

        function showHeroImage(index) {
            heroIndex = index;
            heroWrapper.style.opacity = 0.8;

            setTimeout(() => {
                heroWrapper.style.backgroundImage = `url('${heroImages[heroIndex]}')`;
                heroWrapper.style.opacity = 1;

                const dots = document.querySelectorAll(".main-hero-dots span");
                dots.forEach((dot) => dot.classList.remove("active"));
                dots[heroIndex].classList.add("active");
            }, 500);
        }

        function heroAutoSlide() {
            heroIndex = (heroIndex + 1) % heroImages.length;
            showHeroImage(heroIndex);
        }

        function startHeroAutoSlide() {
            heroSliderInterval = setInterval(heroAutoSlide, 4000);
        }

        function resetHeroAutoSlide() {
            clearInterval(heroSliderInterval);
            startHeroAutoSlide();
        }

        // Initialize Hero Slider
        createHeroDots();
        showHeroImage(0);
        startHeroAutoSlide();

        // === SIDEBAR TOGGLE ===
        const sidebarToggle = document.getElementById("openSidebar");
        const sidebarClose = document.getElementById("closeSidebar");
        const sidebar = document.getElementById("customSidebar");

        if (sidebarToggle && sidebarClose && sidebar) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.add("active");
            });

            sidebarClose.addEventListener("click", () => {
                sidebar.classList.remove("active");
            });
        }
    </script>
@endsection
