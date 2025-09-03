@extends('layouts.website')
@section('meta_title', isset($update) ? $update->meta_title : '')
@section('meta_description', isset($update) ? $update->meta_desc : '')
@section('meta_keywords', isset($update) ? $update->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner3"></div>
        @include('layouts.navbar')
        <div class="hero-content1">
            <h1>rules-and-regulations</h1>
        </div>
    </div>
    <!-- rules-zand-regulations-start -->
    <div class="rule-andregulaton-main-div">
        @if(isset($rules) && $rules->count())
            @foreach($rules as $index => $rule)
                <div class="rules-section">
                    <div class="rules-logo">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="rules-content">
                        <h1>{{ $rule->title }}</h1>
                        @if(is_array($rule->points))
                            <ul>
                                @foreach($rule->points as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
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
                            <h2 style="margin: 0 0 0.25rem 0; font-size: 1.35rem; font-weight: 700; color: #b71c1c;">No Rules & Regulations</h2>
                            <div style="font-size: 1rem; color: #b71c1c;">
                                There are currently no rules and regulations available.<br>
                                Please check back later.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
