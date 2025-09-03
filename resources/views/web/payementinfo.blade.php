@extends('layouts.website')
@section('meta_title', isset($paymentInfo->meta_title) ? $paymentInfo->meta_title : '')
@section('meta_description', isset($paymentInfo->meta_description) ? $paymentInfo->meta_description : '')
@section('meta_keywords', isset($paymentInfo->meta_keywords) ? $paymentInfo->meta_keywords : '')
<style>
    .pricing-structure-section {
        background: #faf7f2;
        border-radius: 12px;
        padding: 36px 24px 28px 24px;
        margin: 36px auto 0 auto;
        box-shadow: 0 2px 12px rgba(79, 138, 139, 0.06);
        font-family: 'Segoe UI', 'Arial', sans-serif;
    }

    .pricing-structure-section h2 {
        text-align: center;
        font-size: 2em;
        font-weight: 600;
        color: #7c6f57;
        margin-bottom: 28px;
        letter-spacing: 0.01em;
    }

    .pricing-structure-section ul {
        margin: 0 0 18px 0;
        padding-left: 22px;
    }

    .pricing-structure-section li {
        font-size: 1.08em;
        margin-bottom: 12px;
        color: #222;
        line-height: 1.7;
    }

    .pricing-structure-section li strong {
        font-weight: 600;
    }

    .pricing-structure-section .highlight {
        color: #b91c1c;
        font-weight: 700;
    }

    .pricing-structure-section .note-section {
        background: #f5e9d7;
        border-radius: 8px;
        padding: 18px 16px;
        margin: 30px 0 18px 0;
        text-align: center;
    }

    .pricing-structure-section .note-section p {
        color: #a16207;
        font-size: 1.08em;
        margin: 0 0 6px 0;
        font-style: italic;
    }

    .pricing-structure-section .subject-change {
        color: #6b4f1d;
        font-weight: 600;
        font-size: 1.13em;
        margin-top: 8px;
    }

    .pricing-structure-section .disclaimer {
        margin-top: 22px;
        font-size: 0.98em;
        color: #444;
        text-align: center;
    }

    .pricing-structure-section .disclaimer a {
        color: #4F8A8B;
        text-decoration: underline;
    }
</style>
@section('content')
    <div class="header-main">
        <div class="hero-banner3"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>Payment Information</h1>
        </div>
    </div>
    <div class="homepage-seaction3-main">

         <div class="pricing-structure-section">
              @if(isset($paymentInfo) && is_array($paymentInfo->title) && is_array($paymentInfo->points))
                  @foreach($paymentInfo->title as $idx => $title)
                      <h2>
                          {{ $title ?? 'Pricing Structure' }}
                      </h2>
                      <ul>
                          @if(isset($paymentInfo->points[$idx]) && is_array($paymentInfo->points[$idx]) && count($paymentInfo->points[$idx]) > 0)
                              @foreach($paymentInfo->points[$idx] as $point)
                                  <li>{!! $point !!}</li>
                              @endforeach
                          @else
                              <li>No payment information available.</li>
                          @endif
                      </ul>
                  @endforeach
              @else
                  <h2>Pricing Structure</h2>
                  <ul>
                      <li>No payment information available.</li>
                  </ul>
              @endif
            @if(isset($paymentInfo->note))
            <div class="note-section">
                <p>
                    Please Note:<br>
                <p>
                    {!! isset($paymentInfo->note) ? $paymentInfo->note : '' !!}
                </p>
            </div>
            @endif
        </div>
    </div>
@endsection
