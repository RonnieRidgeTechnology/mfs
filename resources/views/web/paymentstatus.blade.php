@extends('layouts.website')
@section('meta_title', $paymentStatus->meta_title ?? '')
@section('meta_description', $paymentStatus->meta_description ?? '')
@section('meta_keywords', $paymentStatus->meta_keywords ?? '')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">

<style>
    .paymentstatus-header {
        background: #7b6a4d;
        color: #fff;
        padding: 18px 0 10px 0;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
    }

    .paymentstatus-info {
        background: #f5f0e6;
        padding: 18px 0 10px 0;
        text-align: center;
        color: #333;
    }

    .paymentstatus-info p {
        margin: 0 0 8px 0;
        font-size: 15px;
    }

    .paymentstatus-updated {
        text-align: center;
        margin: 18px 0 10px 0;
        color: #7b6a4d;
        font-weight: bold;
        font-size: 16px;
    }

    .paymentstatus-excel-container {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
    }

    #excelPreview {
        width: 100%;
        max-width: 1100px;
        height: 600px;
        border: 1px solid #ccc;
        background: #fff;
        overflow: hidden;
    }

    @media (max-width: 1200px) {
        #excelPreview {
            height: 400px;
        }
    }

    @media (max-width: 600px) {

        .paymentstatus-header,
        .paymentstatus-info,
        .paymentstatus-updated {
            font-size: 13px;
            padding: 10px 0;
        }

        #excelPreview {
            height: 250px;
        }
    }
</style>

@section('content')
    <div class="header-main">
        <div class="hero-banner5"></div>
        @include('layouts.navbar')
        <div class="hero-content1">
            <h1>Membership Payment Status</h1>
        </div>
    </div>

    <div class="paymentstatus-header">
        {{ $paymentStatus->title ?? '' }}
    </div>

    <div class="paymentstatus-info">
        <p>{!! $paymentStatus->description ?? '' !!}</p>
    </div>

    <div class="paymentstatus-updated">
        {{ $paymentStatus->excel_title ?? '' }}
    </div>

    <div class="paymentstatus-excel-container">
        @if(!empty($paymentStatus->excel_file))
            @php
                // If the file is a Google Drive link, convert to viewer format
                $excelFile = $paymentStatus->excel_file ?? '';
                $iframeSrc = $excelFile;

                // Google Drive share link patterns
                if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $excelFile, $matches)) {
                    $fileId = $matches[1];
                    $iframeSrc = "https://drive.google.com/file/d/{$fileId}/preview";
                } elseif (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $excelFile, $matches)) {
                    $fileId = $matches[1];
                    $iframeSrc = "https://drive.google.com/file/d/{$fileId}/preview";
                } elseif (preg_match('/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/', $excelFile, $matches)) {
                    $fileId = $matches[1];
                    $iframeSrc = "https://docs.google.com/spreadsheets/d/{$fileId}/preview";
                }
            @endphp
            <iframe id="excelPreview" src="{{ $iframeSrc }}" frameborder="0"></iframe>
            <div
                style="text-align: center; margin-top: -3px; position: relative; transform: translateX(-70px) translateY(4px);">
                <a href="{{ $paymentStatus->excel_file }}" target="_blank" rel="noopener"
                    style="display: inline-block; position: relative; width: 48px; height: 48px; background: rgba(180,180,180,0.18); border-radius: 6px; transition: background 0.18s;"
                    onmouseover="this.style.background='rgba(120,120,120,0.22)';"
                    onmouseout="this.style.background='rgba(180,180,180,0.18)';" title="Open Excel File in New Tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <rect x="4" y="4" width="12" height="12" rx="2" fill="#fff" stroke="#bbb" stroke-width="1.5" />
                        <path d="M14 4V2h6v6h-2V6.41l-7.29 7.3-1.42-1.42L16.59 4H14z" fill="#2563eb" />
                    </svg>
                </a>
            </div>
        @else
            <div style="color: #b00; padding: 20px; background: #fff3f3; border: 1px solid #fbb;">
                No payment status Excel file available.
            </div>
        @endif
    </div>

@endsection
