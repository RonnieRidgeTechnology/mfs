@extends('layouts.website')
@section('meta_title', isset($newmember->meta_title) ? $newmember->meta_title : '')
@section('meta_description', isset($newmember->meta_desc) ? $newmember->meta_desc : '')
@section('meta_keywords', isset($newmember->meta_keyword) ? $newmember->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner4"></div>
        @include('layouts.navbar')
        <div class="hero-content1">
            <h1>New Membership</h1>
        </div>
    </div>
    <!-- homepage-seaction2-start -->
    <div class="rule-andregulaton-main-div">
        <div class="rule-andregulaton-main-title">
            <h1>New Membership Rules & Regulations</h1>
        </div>
        @if($rules->isEmpty())
            <div class="no-rules-message"
                style="display: flex; justify-content: center; align-items: center; min-height: 180px;">
                <div
                    style="background: #fff0f0; border: 1.5px solid #e53935; color: #b71c1c; border-radius: 8px; padding: 2rem 2.5rem; box-shadow: 0 2px 12px rgba(229,57,53,0.08); display: inline-block; max-width: 420px;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 2.2rem; color: #e53935;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" fill="#e53935" />
                                <rect x="11" y="7" width="2" height="7" rx="1" fill="#fff" />
                                <rect x="11" y="16" width="2" height="2" rx="1" fill="#fff" />
                            </svg>
                        </span>
                        <div>
                            <h2 style="margin: 0 0 0.25rem 0; font-size: 1.35rem; font-weight: 700; color: #b71c1c;">No Rules &
                                Regulations</h2>
                            <div style="font-size: 1rem; color: #b71c1c;">
                                No rules and regulations for new members are available at the moment.<br>
                                Please check again soon.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @foreach($rules as $rule)
                <div class="rules-section">
                    <div class="rules-logo">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="rules-content">
                        <h1>{{ isset($rule->title) ? $rule->title : 'Rules and Regulations for New Members' }}</h1>
                        @if(isset($rule->points) && !empty($rule->points) && is_array($rule->points))
                            <ul>
                                @foreach($rule->points as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div style="color: #b71c1c;">No rule points available.</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- homepage-seaction3-start -->
    <div class="homepage-seaction3-main">
        <div class="homepage-seaction3-mian-context">
            <h1>{{ isset($newmember->title) ? $newmember->title : '' }}</h1>
            <p>
                {{ isset($newmember->desc) ? $newmember->desc : '' }}
            </p>
        </div>
        @if(isset($newmember->pdf) && !empty($newmember->pdf))
            <div class="pdf-viewer" style="display: flex; justify-content: center; margin: 2rem 0;">
                <iframe src="{{ asset($newmember->pdf) }}" width="80%" height="600px"
                    style="border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);"
                    allow="autoplay"></iframe>
            </div>
        @else
            <div style="text-align: center; color: #b71c1c; margin: 2rem 0;">
                <strong>No membership PDF available at the moment.</strong>
            </div>
        @endif
        <div class="homepage-seaction3-mian-pdf">
            @if(isset($newmember->pdf) && !empty($newmember->pdf))
                <a href="{{ asset($newmember->pdf) }}" class="pdf-upload-btn" id="download-pdf-btn" download
                    style="display: inline-block; text-align: center; cursor: pointer;">
                    Download PDF
                </a>
            @else
                <span class="pdf-upload-btn" style="opacity: 0.6; cursor: not-allowed;">Download PDF</span>
            @endif

            <div id="pdf-container">
                <iframe id="pdf-preview"></iframe>
                <button class="delete-btn" id="delete-pdf">&times;</button>
            </div>
        </div>
    </div>
@endsection
