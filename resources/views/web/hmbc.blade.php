@extends('layouts.website')
@section('meta_title', isset($hmbc->meta_title) ? $hmbc->meta_title : '')
@section('meta_description', isset($hmbc->meta_desc) ? $hmbc->meta_desc : '')
@section('meta_keywords', isset($hmbc->meta_keyword) ? $hmbc->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner5"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>HMBC</h1>
        </div>
    </div>
    <!-- hmbc-start -->
    <div class="hmbc-main">
        <div class="hmbc-main-context">
            <h1>{{ isset($hmbc->title) ? $hmbc->title : '' }}</h1>
            <p>{!! isset($hmbc->desc) ? $hmbc->desc : '' !!}
            </p>
        </div>
    </div>
    <!-- .hmbc-seaction1-strat -->
    <div class="hmbc-seaction1-main">
        <div class="hmbc-seaction1-context">
            <h1>{{ isset($hmbc->location_title) ? $hmbc->location_title : '' }}</h1>
            <p>
               {{ isset($hmbc->location_desc) ? $hmbc->location_desc : '' }}
            </p>
        </div>

        <div class="map-container">
            <iframe
                src="{{ isset($hmbc->location_link) ? $hmbc->location_link : '' }}"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
    <!-- .hmbc-seaction2-strat -->
    <div class="homepage-seaction3-main">
        <div class="homepage-seaction3-mian-context">
            <h1>{{ isset($hmbc->member_title) ? $hmbc->member_title : '' }}</h1>
            <p>
                {{ isset($hmbc->member_desc) ? $hmbc->member_desc : '' }}
            </p>
        </div>
        <div class="homepage-seaction3-mian-pdf">
             @if(isset($hmbc->pdf) && !empty($hmbc->pdf))
                <a href="{{ asset($hmbc->pdf) }}" class="pdf-download-btn" download>
                    Download PDF
                </a>
            @else
                <span>No PDF available.</span>
            @endif

            <div id="pdf-container">
                <iframe id="pdf-preview"></iframe>
                <button class="delete-btn" id="delete-pdf">&times;</button>
            </div>
        </div>
    </div>
@endsection
