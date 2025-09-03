@extends('layouts.website')
@section('meta_title', isset($about->meta_title) ? $about->meta_title : '')
@section('meta_description', isset($about->meta_description) ? $about->meta_description : '')
@section('meta_keywords', isset($about->meta_keywords) ? $about->meta_keywords : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner2"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>About Us</h1>
        </div>
    </div>
    <div style="margin: 40px auto 0 auto; background: #f9f7f1; border-radius: 8px; box-shadow: 0 1px 4px 0 rgba(37,99,235,0.08); padding: 32px 28px 24px 28px;">
        @if(isset($about) && isset($about->title) && is_array($about->title) && isset($about->points) && is_array($about->points))
            @foreach($about->title as $idx => $title)
                <h1 style="text-align: center; font-size: 1.7rem; font-weight: 700; color: #7c6a4d; margin-bottom: 24px;margin-top:20px;">
                    {{ isset($title) ? $title : 'Our Constitution' }}
                </h1>
                <ul style="list-style: disc inside; color: #3d3d3d; font-size: 1.08rem; line-height: 1.7; padding-left: 0;">
                    @if(isset($about->points[$idx]) && is_array($about->points[$idx]) && count($about->points[$idx]) > 0)
                        @foreach($about->points[$idx] as $point)
                            <li>{!! isset($point) ? $point : '' !!}</li>
                        @endforeach
                    @else
                        <li>No information available.</li>
                    @endif
                </ul>
            @endforeach
        @else
            <div style="max-width: 600px; margin: 0 auto;">
                <div
                    style="display: flex; align-items: flex-start; background: #fee2e2; border: 1px solid #f87171; color: #991b1b; border-radius: 8px; padding: 20px 24px; box-shadow: 0 1px 4px 0 rgba(239,68,68,0.08);">
                    <svg style="flex-shrink: 0; margin-right: 16px;" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" fill="#f87171" />
                        <path d="M12 8v4m0 4h.01" stroke="#fff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <div>
                        <h1 style="margin: 0 0 8px 0; font-size: 1.3rem; font-weight: 700; color: #991b1b;">
                            Our Constitution
                        </h1>
                        <ul
                            style="list-style: disc inside; color: #991b1b; font-size: 1.08rem; line-height: 1.7; padding-left: 0; margin: 0;">
                             <li>Information will be provided soon. Please check back later.</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
