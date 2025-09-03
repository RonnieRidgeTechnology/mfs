@extends('layouts.website')
@section('meta_title', isset($council->meta_title) ? $council->meta_title : '')
@section('meta_description', isset($council->meta_desc) ? $council->meta_desc : '')
@section('meta_keywords', isset($council->meta_keyword) ? $council->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner5"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>HMBC</h1>
        </div>
    </div>
    <div class="during-context">
        <h1>{{ isset($council->title) ? $council->title : '' }}</h1>
        <p>
        {{ isset($council->desc) ? $council->desc : '' }}
        </p>
        @if(isset($council->image) && !empty($council->image))
            <div class="council-image">
                <img src="{{ asset($council->image) }}" alt="{{ isset($council->title) ? $council->title : '' }}" style="max-width:100%;height:auto;">
            </div>
        @endif
      </div>
@endsection
