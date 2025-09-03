@extends('layouts.admin')
<style>
    .form-floating {
        position: relative;
    }
    .form-floating input,
    .form-floating select {
        width: 100%;
        padding: 1.25rem 0.75rem 0.5rem 0.75rem;
        font-size: 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 8px;
        background: transparent;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-floating input:focus,
    .form-floating select:focus {
        border-color: #2563eb;
    }
    .form-floating label {
        position: absolute;
        top: 1.05rem;
        left: 0.85rem;
        color: #6b7280;
        font-size: 1rem;
        pointer-events: none;
        background: #fff;
        padding: 0 4px;
        transition: 0.2s;
        z-index: 2;
    }
    .form-floating input:not(:placeholder-shown) + label,
    .form-floating input:focus + label,
    .form-floating select:focus + label,
    .form-floating select:not([value=""]) + label {
        top: -0.7rem;
        left: 0.65rem;
        font-size: 0.85rem;
        color: #2563eb;
        background: #fff;
        padding: 0 4px;
    }
    .form-floating input.is-invalid,
    .form-floating select.is-invalid {
        border-color: #dc2626;
    }
    .form-floating input.is-valid,
    .form-floating select.is-valid {
        border-color: #16a34a;
    }
    .invalid-feedback {
        color: #dc2626;
        font-size: 0.92em;
        margin-top: 2px;
        display: block !important;
    }
    .valid-feedback {
        color: #16a34a;
        font-size: 0.92em;
        margin-top: 2px;
        display: block !important;
    }
</style>

@section('content')
<main class="main-content">
    @include('layouts.header')
    <div class="content">
        <div class="table-section">
            <div class="table-header">
                <h2 style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-gear" style="color: #4F8A8B; font-size: 1.3em;"></i>
                    Payment Status Settings
                </h2>
            </div>

            <form method="POST" action="{{ route('payment_status.update', ['id' => $paymentStatus->id ?? null]) }}" class="add-form" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(79,138,139,0.08); padding: 36px 32px 28px 32px;" enctype="multipart/form-data">
                @csrf

                <div style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3 style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-address-book" style="color: #4F8A8B;"></i>
                        Payment Status Information
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Please update the payment status details as needed.
                    </div>

                    {{-- Title --}}
                    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px;">
                        <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : (old('title', $paymentStatus->title ?? '') ? 'is-valid' : '') }}"
                                   value="{{ old('title', $paymentStatus->title ?? '') }}"
                                   placeholder=" ">
                            <label for="title">Title</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('title', $paymentStatus->title ?? ''))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    </div>

                     {{-- Description --}}
                     <div class="form-group" style="margin-top: 16px;">
                        <label for="description" style="margin-bottom: 6px; font-weight: 500;">Description</label>
                        <textarea name="description"
                                  id="description"
                                  class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : (old('description', $paymentStatus->description ?? '') ? 'is-valid' : '') }}"
                                  style="min-height: 90px;"
                        >{{ old('description', $paymentStatus->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('description', $paymentStatus->description ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>


                    {{-- Meta Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_title"
                               id="meta_title"
                               class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : (old('meta_title', $paymentStatus->meta_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_title', $paymentStatus->meta_title ?? '') }}"
                               placeholder=" ">
                        <label for="meta_title">Meta Title</label>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_title', $paymentStatus->meta_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="meta_description"
                                  id="meta_description"
                                  class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : (old('meta_description', $paymentStatus->meta_description ?? '') ? 'is-valid' : '') }}"
                                  placeholder=" "
                                  style="min-height: 70px;">{{ old('meta_description', $paymentStatus->meta_description ?? '') }}</textarea>
                        <label for="meta_description">Meta Description</label>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_description', $paymentStatus->meta_description ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Keywords --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_keywords"
                               id="meta_keywords"
                               class="form-control {{ $errors->has('meta_keywords') ? 'is-invalid' : (old('meta_keywords', $paymentStatus->meta_keywords ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_keywords', $paymentStatus->meta_keywords ?? '') }}"
                               placeholder=" ">
                        <label for="meta_keywords">Meta Keywords</label>
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_keywords', $paymentStatus->meta_keywords ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Excel Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="excel_title"
                               id="excel_title"
                               class="form-control {{ $errors->has('excel_title') ? 'is-invalid' : (old('excel_title', $paymentStatus->excel_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('excel_title', $paymentStatus->excel_title ?? '') }}"
                               placeholder=" ">
                        <label for="excel_title">Excel Title</label>
                        @error('excel_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('excel_title', $paymentStatus->excel_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Excel File --}}
                     <div class="form-group" style="margin-top: 16px;">
                        <label for="excel_file" style="font-weight: 500; color: #4F8A8B;">Excel File Link or Google Sheet URL</label>
                        <input type="url"
                               name="excel_file"
                               id="excel_file"
                               class="form-control {{ $errors->has('excel_file') ? 'is-invalid' : (old('excel_file', $paymentStatus->excel_file ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('excel_file', $paymentStatus->excel_file ?? '') }}"
                               placeholder="Paste a direct link to an Excel/CSV/PDF file or a Google Sheet URL">
                        <small style="color: #888;">
                            Enter a direct link to an Excel/CSV/PDF file (e.g., Dropbox, Google Drive, etc.) or a Google Sheet URL.
                        </small>
                        @error('excel_file')
                            <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                        @enderror
                        @if(!empty($paymentStatus->excel_file))
                            <div style="margin-top: 8px;">
                                <a href="{{ $paymentStatus->excel_file }}" target="_blank" style="color: #2563eb; text-decoration: underline;">
                                    View Current File/Sheet
                                </a>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="form-actions" style="margin-top: 10px; text-align: right;">
                    <button type="submit" class="action-button primary" style="background: #4F8A8B; color: #fff; border: none; border-radius: 6px; padding: 10px 28px; font-size: 1.08em; font-weight: 600; box-shadow: 0 2px 8px rgba(79,138,139,0.08); transition: background 0.2s;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 7px;"></i>
                        Save Payment Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>
@endsection
