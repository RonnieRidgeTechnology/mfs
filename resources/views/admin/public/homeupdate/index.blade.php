@extends('layouts.admin')
<style>
    .form-floating {
        position: relative;
    }
    .form-floating input,
    .form-floating select,
    .form-floating textarea {
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
    .form-floating textarea {
        min-height: 80px;
        resize: vertical;
    }
    .form-floating input:focus,
    .form-floating select:focus,
    .form-floating textarea:focus {
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
    .form-floating select:not([value=""]) + label,
    .form-floating textarea:not(:placeholder-shown) + label,
    .form-floating textarea:focus + label {
        top: -0.7rem;
        left: 0.65rem;
        font-size: 0.85rem;
        color: #2563eb;
        background: #fff;
        padding: 0 4px;
    }
    .form-floating input.is-invalid,
    .form-floating select.is-invalid,
    .form-floating textarea.is-invalid {
        border-color: #dc2626;
    }
    .form-floating input.is-valid,
    .form-floating select.is-valid,
    .form-floating textarea.is-valid {
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
    /* Modal Styles */
    .custom-modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.45);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-modal-content {
        background: #fff;
        border-radius: 12px;
        padding: 18px 18px 10px 18px;
        max-width: 96vw;
        max-height: 90vh;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .custom-modal-content img {
        max-width: 80vw;
        max-height: 70vh;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(37,99,235,0.08);
    }
    .custom-modal-close {
        position: absolute;
        top: 10px;
        right: 14px;
        background: none;
        border: none;
        font-size: 1.7rem;
        color: #dc2626;
        cursor: pointer;
        z-index: 2;
        transition: color 0.2s;
    }
    .custom-modal-close:hover {
        color: #a71d2a;
    }
</style>
<style>
    .btn-modern:hover, .btn-modern:focus {
        background: linear-gradient(90deg, #4F8A8B 0%, #2563eb 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 16px rgba(79,138,139,0.12);
    }
    .btn-icon {
        border: none;
        background: #f8f9fa;
        color: #dc3545;
        transition: background 0.2s, color 0.2s;
    }
    .btn-icon:hover, .btn-icon:focus {
        background: #ffeaea;
        color: #a71d2a;
    }
    .modern-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.08);
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
                    Home Update Settings
                </h2>
            </div>

             <form method="POST" action="{{ route('home_update.update') }}" class="add-form" enctype="multipart/form-data" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(79,138,139,0.08); padding: 36px 32px 28px 32px;">
                @csrf
                <div style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3 style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-house" style="color: #4F8A8B;"></i>
                        Home Page Information
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Please keep the home page details current for your website.
                    </div>

                    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px;">
                        {{-- Main Title --}}
                        <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                            <input type="text"
                                   name="main_title"
                                   id="main_title"
                                   class="form-control {{ $errors->has('main_title') ? 'is-invalid' : (old('main_title', $homeUpdate->main_title ?? '') ? 'is-valid' : '') }}"
                                   value="{{ old('main_title', $homeUpdate->main_title ?? '') }}"
                                   placeholder=" ">
                            <label for="main_title">Main Title</label>
                            @error('main_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('main_title', $homeUpdate->main_title ?? ''))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Main Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="main_desc"
                                  id="main_desc"
                                  rows="3"
                                  class="form-control {{ $errors->has('main_desc') ? 'is-invalid' : (old('main_desc', $homeUpdate->main_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('main_desc', $homeUpdate->main_desc ?? '') }}</textarea>
                        <label for="main_desc">Main Description</label>
                        @error('main_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('main_desc', $homeUpdate->main_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Main Image 1 --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="main_image1" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Main Image 1 (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="main_image1"
                               id="main_image1"
                               class="form-control {{ $errors->has('main_image1') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->main_image1) && $homeUpdate->main_image1)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->main_image1)) }}"
                                   data-title="Current Main Image 1"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Image 1
                                </a>
                            </div>
                        @endif
                        @error('main_image1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Main Image 2 --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="main_image2" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Main Image 2 (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="main_image2"
                               id="main_image2"
                               class="form-control {{ $errors->has('main_image2') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->main_image2) && $homeUpdate->main_image2)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->main_image2)) }}"
                                   data-title="Current Main Image 2"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Image 2
                                </a>
                            </div>
                        @endif
                        @error('main_image2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 1 Main Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="section1_main_title"
                               id="section1_main_title"
                               class="form-control {{ $errors->has('section1_main_title') ? 'is-invalid' : (old('section1_main_title', $homeUpdate->section1_main_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('section1_main_title', $homeUpdate->section1_main_title ?? '') }}"
                               placeholder=" ">
                        <label for="section1_main_title">Section 1 Main Title</label>
                        @error('section1_main_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section1_main_title', $homeUpdate->section1_main_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 1 Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="section1_title"
                               id="section1_title"
                               class="form-control {{ $errors->has('section1_title') ? 'is-invalid' : (old('section1_title', $homeUpdate->section1_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('section1_title', $homeUpdate->section1_title ?? '') }}"
                               placeholder=" ">
                        <label for="section1_title">Section 1 Title</label>
                        @error('section1_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section1_title', $homeUpdate->section1_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 1 Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="section1_desc"
                                  id="section1_desc"
                                  rows="3"
                                  class="form-control {{ $errors->has('section1_desc') ? 'is-invalid' : (old('section1_desc', $homeUpdate->section1_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('section1_desc', $homeUpdate->section1_desc ?? '') }}</textarea>
                        <label for="section1_desc">Section 1 Description</label>
                        @error('section1_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section1_desc', $homeUpdate->section1_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 1 Points (Array) --}}
                      <div class="form-group" style="margin-top: 1.5rem;">
                        <label style="font-weight: 600; color: #2563eb; margin-bottom: 0.5rem; display: block; font-size: 1.1rem;">
                            Section 1 Points
                        </label>
                        @php
                            $points = old('section1_points', $homeUpdate->section1_points ?? []);
                            if (!is_array($points)) $points = json_decode($points, true) ?: [];
                            if (empty($points)) $points = [''];
                        @endphp
                        <div id="section1-points-list">
                            @foreach($points as $i => $point)
                                <div class="modern-point-row" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <div class="form-floating flex-grow-1" style="margin-bottom: 0; position: relative;flex: 1;">
                                        <input type="text"
                                               name="section1_points[]"
                                               class="form-control modern-input {{ $errors->has('section1_points.' . $i) ? 'is-invalid' : ($point ? 'is-valid' : '') }}"
                                               value="{{ $point }}"
                                               placeholder=" ">
                                        <label>Point {{ $i+1 }}</label>
                                        @if($errors->has('section1_points.' . $i))
                                            <div class="invalid-feedback">{{ $errors->first('section1_points.' . $i) }}</div>
                                        @elseif(!empty($point))
                                            <div class="valid-feedback">Looks good!</div>
                                        @endif
                                    </div>
                                    <button type="button"
                                            class="btn btn-icon btn-outline-danger remove-point-btn"
                                            title="Remove"
                                            style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 50%; font-size: 1.25rem; transition: background 0.2s;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button"
                                class="btn btn-gradient btn-modern"
                                id="add-point-btn"
                                style="margin-top: 0.5rem; padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; background: linear-gradient(90deg, #2563eb 0%, #4F8A8B 100%); color: #fff; border: none; box-shadow: 0 2px 8px rgba(79,138,139,0.08);">
                            <i class="fa fa-plus" style="margin-right: 0.5rem;"></i> Add Point
                        </button>


                    </div>

                    {{-- Section 1 Image 1 --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="section1_image1" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Section 1 Image 1 (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="section1_image1"
                               id="section1_image1"
                               class="form-control {{ $errors->has('section1_image1') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->section1_image1) && $homeUpdate->section1_image1)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->section1_image1)) }}"
                                   data-title="Current Section 1 Image 1"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Section 1 Image 1
                                </a>
                            </div>
                        @endif
                        @error('section1_image1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 1 Image 2 --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="section1_image2" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Section 1 Image 2 (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="section1_image2"
                               id="section1_image2"
                               class="form-control {{ $errors->has('section1_image2') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->section1_image2) && $homeUpdate->section1_image2)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->section1_image2)) }}"
                                   data-title="Current Section 1 Image 2"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Section 1 Image 2
                                </a>
                            </div>
                        @endif
                        @error('section1_image2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 1 Image 3 --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="section1_image3" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Section 1 Image 3 (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="section1_image3"
                               id="section1_image3"
                               class="form-control {{ $errors->has('section1_image3') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->section1_image3) && $homeUpdate->section1_image3)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->section1_image3)) }}"
                                   data-title="Current Section 1 Image 3"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Section 1 Image 3
                                </a>
                            </div>
                        @endif
                        @error('section1_image3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 2 Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="section2_title"
                               id="section2_title"
                               class="form-control {{ $errors->has('section2_title') ? 'is-invalid' : (old('section2_title', $homeUpdate->section2_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('section2_title', $homeUpdate->section2_title ?? '') }}"
                               placeholder=" ">
                        <label for="section2_title">Section 2 Title</label>
                        @error('section2_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section2_title', $homeUpdate->section2_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 2 Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="section2_desc"
                                  id="section2_desc"
                                  rows="3"
                                  class="form-control {{ $errors->has('section2_desc') ? 'is-invalid' : (old('section2_desc', $homeUpdate->section2_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('section2_desc', $homeUpdate->section2_desc ?? '') }}</textarea>
                        <label for="section2_desc">Section 2 Description</label>
                        @error('section2_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section2_desc', $homeUpdate->section2_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 3 Main Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="section3_main_title"
                               id="section3_main_title"
                               class="form-control {{ $errors->has('section3_main_title') ? 'is-invalid' : (old('section3_main_title', $homeUpdate->section3_main_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('section3_main_title', $homeUpdate->section3_main_title ?? '') }}"
                               placeholder=" ">
                        <label for="section3_main_title">Section 3 Main Title</label>
                        @error('section3_main_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section3_main_title', $homeUpdate->section3_main_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 3 Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="section3_title"
                               id="section3_title"
                               class="form-control {{ $errors->has('section3_title') ? 'is-invalid' : (old('section3_title', $homeUpdate->section3_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('section3_title', $homeUpdate->section3_title ?? '') }}"
                               placeholder=" ">
                        <label for="section3_title">Section 3 Title</label>
                        @error('section3_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section3_title', $homeUpdate->section3_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 3 Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="section3_desc"
                                  id="section3_desc"
                                  rows="3"
                                  class="form-control {{ $errors->has('section3_desc') ? 'is-invalid' : (old('section3_desc', $homeUpdate->section3_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('section3_desc', $homeUpdate->section3_desc ?? '') }}</textarea>
                        <label for="section3_desc">Section 3 Description</label>
                        @error('section3_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('section3_desc', $homeUpdate->section3_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Section 3 Image --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="section3_image" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">Section 3 Image (optional, jpg/png, max 4MB)</label>
                        <input type="file"
                               name="section3_image"
                               id="section3_image"
                               class="form-control {{ $errors->has('section3_image') ? 'is-invalid' : '' }}"
                               accept="image/*">
                        @if(isset($homeUpdate->section3_image) && $homeUpdate->section3_image)
                            <div style="margin-top: 6px;">
                                <a href="javascript:void(0);"
                                   class="view-image-modal-link"
                                   data-image="{{ asset('uploads/homeupdate/' . basename($homeUpdate->section3_image)) }}"
                                   data-title="Current Section 3 Image"
                                   style="color: #2563eb; text-decoration: underline;">
                                    View current Section 3 Image
                                </a>
                            </div>
                        @endif
                        @error('section3_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Footer Main Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="footer_main_title"
                               id="footer_main_title"
                               class="form-control {{ $errors->has('footer_main_title') ? 'is-invalid' : (old('footer_main_title', $homeUpdate->footer_main_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('footer_main_title', $homeUpdate->footer_main_title ?? '') }}"
                               placeholder=" ">
                        <label for="footer_main_title">Footer Main Title</label>
                        @error('footer_main_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('footer_main_title', $homeUpdate->footer_main_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Footer Main Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="footer_main_desc"
                                  id="footer_main_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('footer_main_desc') ? 'is-invalid' : (old('footer_main_desc', $homeUpdate->footer_main_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('footer_main_desc', $homeUpdate->footer_main_desc ?? '') }}</textarea>
                        <label for="footer_main_desc">Footer Main Description</label>
                        @error('footer_main_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('footer_main_desc', $homeUpdate->footer_main_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Footer Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="footer_title"
                               id="footer_title"
                               class="form-control {{ $errors->has('footer_title') ? 'is-invalid' : (old('footer_title', $homeUpdate->footer_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('footer_title', $homeUpdate->footer_title ?? '') }}"
                               placeholder=" ">
                        <label for="footer_title">Footer Title</label>
                        @error('footer_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('footer_title', $homeUpdate->footer_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Footer Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="footer_desc"
                                  id="footer_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('footer_desc') ? 'is-invalid' : (old('footer_desc', $homeUpdate->footer_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('footer_desc', $homeUpdate->footer_desc ?? '') }}</textarea>
                        <label for="footer_desc">Footer Description</label>
                        @error('footer_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('footer_desc', $homeUpdate->footer_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_title"
                               id="meta_title"
                               class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : (old('meta_title', $homeUpdate->meta_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_title', $homeUpdate->meta_title ?? '') }}"
                               placeholder=" ">
                        <label for="meta_title">Meta Title</label>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_title', $homeUpdate->meta_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="meta_desc"
                                  id="meta_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('meta_desc') ? 'is-invalid' : (old('meta_desc', $homeUpdate->meta_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Description"
                                  style="resize: vertical;">{{ old('meta_desc', $homeUpdate->meta_desc ?? '') }}</textarea>
                        <label for="meta_desc">Meta Description</label>
                        @error('meta_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_desc', $homeUpdate->meta_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Keyword --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_keyword"
                               id="meta_keyword"
                               class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : (old('meta_keyword', $homeUpdate->meta_keyword ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_keyword', $homeUpdate->meta_keyword ?? '') }}"
                               placeholder=" ">
                        <label for="meta_keyword">Meta Keyword</label>
                        @error('meta_keyword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_keyword', $homeUpdate->meta_keyword ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 10px; text-align: right;">
                    <button type="submit" class="action-button primary" style="background: #4F8A8B; color: #fff; border: none; border-radius: 6px; padding: 10px 28px; font-size: 1.08em; font-weight: 600; box-shadow: 0 2px 8px rgba(79,138,139,0.08); transition: background 0.2s;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 7px;"></i>
                        Save Home Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Modal for viewing images -->
<div id="customImageModal" style="display:none;">
    <div class="custom-modal-backdrop" id="customImageModalBackdrop">
        <div class="custom-modal-content">
            <button class="custom-modal-close" id="customImageModalClose" aria-label="Close">&times;</button>
            <div style="margin-bottom: 10px; font-weight: 600; color: #4F8A8B; font-size: 1.08em;" id="customImageModalTitle"></div>
            <img id="customImageModalImg" src="" alt="Image" />
        </div>
    </div>
</div>

<script>
    // Modal logic for image viewing
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const modal = document.getElementById('customImageModal');
        const modalBackdrop = document.getElementById('customImageModalBackdrop');
        const modalImg = document.getElementById('customImageModalImg');
        const modalTitle = document.getElementById('customImageModalTitle');
        const modalClose = document.getElementById('customImageModalClose');

        // Open modal on link click
        document.querySelectorAll('.view-image-modal-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const imgSrc = this.getAttribute('data-image');
                const title = this.getAttribute('data-title') || '';
                modalImg.src = imgSrc;
                modalTitle.textContent = title;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        // Close modal on close button or backdrop click
        function closeModal() {
            modal.style.display = 'none';
            modalImg.src = '';
            document.body.style.overflow = '';
        }
        modalClose.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', function(e) {
            if (e.target === modalBackdrop) {
                closeModal();
            }
        });
        // ESC key closes modal
        document.addEventListener('keydown', function(e) {
            if (modal.style.display === 'block' && e.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pointsList = document.getElementById('section1-points-list');
        const addBtn = document.getElementById('add-point-btn');

        function updateRemoveButtons() {
            const rows = pointsList.querySelectorAll('.modern-point-row');
            rows.forEach((row, idx) => {
                const removeBtn = row.querySelector('.remove-point-btn');
                if (rows.length > 1) {
                    removeBtn.style.visibility = 'visible';
                    removeBtn.onclick = function() {
                        row.remove();
                        updateLabels();
                        updateRemoveButtons();
                    };
                } else {
                    removeBtn.style.visibility = 'hidden';
                    removeBtn.onclick = null;
                }
            });
        }

        function updateLabels() {
            const rows = pointsList.querySelectorAll('.modern-point-row');
            rows.forEach((row, idx) => {
                const label = row.querySelector('label');
                if (label) label.textContent = `Point ${idx + 1}`;
            });
        }

        addBtn.addEventListener('click', function () {
            const count = pointsList.querySelectorAll('.modern-point-row').length;
            const wrapper = document.createElement('div');
            wrapper.className = 'modern-point-row';
            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.gap = '0.5rem';
            wrapper.style.marginBottom = '0.75rem';
            wrapper.innerHTML = `
                <div class="form-floating flex-grow-1" style="margin-bottom: 0; position: relative;flex: 1;">
                    <input type="text" name="section1_points[]" class="form-control modern-input" placeholder=" ">
                    <label>Point ${count + 1}</label>
                </div>
                <button type="button"
                        class="btn btn-icon btn-outline-danger remove-point-btn"
                        title="Remove"
                        style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 50%; font-size: 1.25rem;">
                    <i class="fa fa-times"></i>
                </button>
            `;
            pointsList.appendChild(wrapper);
            updateLabels();
            updateRemoveButtons();
        });

        updateLabels();
        updateRemoveButtons();
    });
</script>
@endsection
