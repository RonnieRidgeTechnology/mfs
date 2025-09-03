@extends('layouts.website')
@section('meta_title', isset($update->meta_title) ? $update->meta_title : '')
@section('meta_description', isset($update->meta_desc) ? $update->meta_desc : '')
@section('meta_keywords', isset($update->meta_keyword) ? $update->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner2"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>FAQ</h1>
        </div>
    </div>
    <!-- faq-seaction1-start -->
    <div class="faq-seaction2-main-div2">
        <div class="faq-title">
            <h1>{{ isset($update->title) ? $update->title : '' }}</h1>
        </div>
        <div class="faq-main-dive">
            <div class="faq-images">
                <img src="{{ isset($update->image) ? asset($update->image) : '' }}" alt="" />
            </div>
              @if(empty($faq))
              <div class="no-rules-message" style="display: flex; justify-content: center; align-items: center; min-height: 180px;">
                <div style="background: #fff0f0; border: 1.5px solid #e53935; color: #b71c1c; border-radius: 8px; padding: 2rem 2.5rem; box-shadow: 0 2px 12px rgba(229,57,53,0.08); display: inline-block; max-width: 420px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 2.2rem; color: #e53935;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" fill="#e53935"/>
                                <rect x="11" y="7" width="2" height="7" rx="1" fill="#fff"/>
                                <rect x="11" y="16" width="2" height="2" rx="1" fill="#fff"/>
                            </svg>
                        </span>
                        <div>
                            <h2 style="margin: 0 0 0.25rem 0; font-size: 1.35rem; font-weight: 700; color: #b71c1c;">No FAQs</h2>
                            <div style="font-size: 1rem; color: #b71c1c;">
                                 No FAQs are available at the moment.<br>
                                 Please check again soon.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             @else
             <div class="accordion-container">
                @php $i = 1; @endphp
                @foreach($faq as $item)
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <div class="accordion-question">
                                <div class="rules-logo3">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                                {{ isset($item->question) ? $item->question : '' }}
                            </div>
                            <div class="accordion-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6l6 -6" />
                                </svg>
                            </div>
                        </div>
                        <div class="accordion-content">
                            <p>
                                {{ isset($item->answer) ? $item->answer : '' }}
                            </p>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        const accordions = document.querySelectorAll(".accordion-item");

        accordions.forEach((item) => {
            item
                .querySelector(".accordion-header")
                .addEventListener("click", () => {
                    // Close others
                    accordions.forEach(
                        (i) => i !== item && i.classList.remove("active")
                    );

                    // Toggle current
                    item.classList.toggle("active");
                });
        });
    </script>
@endsection
