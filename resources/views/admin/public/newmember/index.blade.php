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
</style>

@section('content')
<main class="main-content">
    @include('layouts.header')
    <div class="content">
        <div class="table-section">
            <div class="table-header">
                <h2 style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-user-plus" style="color: #4F8A8B; font-size: 1.3em;"></i>
                    Update New Member Section
                </h2>
            </div>
            <form method="POST" action="{{ route('new_member.update') }}" class="add-form" enctype="multipart/form-data" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(79,138,139,0.08); padding: 36px 32px 28px 32px;">
                @csrf
                <div style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3 style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-address-card" style="color: #4F8A8B;"></i>
                        Update New Member Section Content
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Please update the content for the New Member section.
                    </div>

                    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px;">
                        {{-- Title --}}
                        <div class="form-group form-floating" style="flex: 1 1 320px; min-width: 320px;">
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : (old('title') ? 'is-valid' : '') }}"
                                   value="{{ old('title', isset($newMember) ? $newMember->title : '') }}"
                                   placeholder=" ">
                            <label for="title">Title</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('title'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea
                            name="desc"
                            id="desc"
                            class="form-control {{ $errors->has('desc') ? 'is-invalid' : (old('desc') ? 'is-valid' : '') }}"
                            placeholder=" ">{{ old('desc', isset($newMember) ? $newMember->desc : '') }}</textarea>
                        <label for="desc">Description</label>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('desc'))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px; margin-top: 16px;">
                        {{-- Meta Title --}}
                        <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                            <input type="text"
                                   name="meta_title"
                                   id="meta_title"
                                   class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : (old('meta_title') ? 'is-valid' : '') }}"
                                   value="{{ old('meta_title', isset($newMember) ? $newMember->meta_title : '') }}"
                                   placeholder=" ">
                            <label for="meta_title">Meta Title</label>
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('meta_title'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                        {{-- Meta Keyword --}}
                        <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                            <input type="text"
                                   name="meta_keyword"
                                   id="meta_keyword"
                                   class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : (old('meta_keyword') ? 'is-valid' : '') }}"
                                   value="{{ old('meta_keyword', isset($newMember) ? $newMember->meta_keyword : '') }}"
                                   placeholder=" ">
                            <label for="meta_keyword">Meta Keyword</label>
                            @error('meta_keyword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('meta_keyword'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Meta Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea
                            name="meta_desc"
                            id="meta_desc"
                            class="form-control {{ $errors->has('meta_desc') ? 'is-invalid' : (old('meta_desc') ? 'is-valid' : '') }}"
                            placeholder=" ">{{ old('meta_desc', isset($newMember) ? $newMember->meta_desc : '') }}</textarea>
                        <label for="meta_desc">Meta Description</label>
                        @error('meta_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_desc'))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- PDF Upload --}}
                     <div class="form-group" style="margin-top: 16px;">
                        <label for="pdf" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">PDF File (optional, .pdf, max 20MB)</label>
                        <div
                            id="pdf-dropzone"
                            style="border: 2px dashed #4F8A8B; border-radius: 8px; padding: 32px 18px; text-align: center; background: #f8fafb; cursor: pointer; transition: border-color 0.2s;"
                            onclick="document.getElementById('pdf').click();"
                            ondragover="event.preventDefault(); this.style.borderColor='#2563eb';"
                            ondragleave="event.preventDefault(); this.style.borderColor='#4F8A8B';"
                            ondrop="event.preventDefault(); handlePdfDrop(event);"
                        >
                            <i class="fa-solid fa-file-pdf" style="font-size: 2.2em; color: #4F8A8B; margin-bottom: 8px;"></i>
                            <div style="font-size: 1.08em; color: #4F8A8B; margin-bottom: 6px;">
                                Drag &amp; drop PDF here, or <span style="color: #2563eb; text-decoration: underline; cursor: pointer;">browse</span>
                            </div>
                            <div id="pdf-filename" style="color: #2563eb; font-size: 0.98em; margin-top: 6px; display: none;"></div>
                        </div>
                        <input type="file"
                               name="pdf"
                               id="pdf"
                               style="display: none;"
                               class="{{ $errors->has('pdf') ? 'is-invalid' : '' }}"
                               accept="application/pdf"
                               onchange="showPdfFilename(this)">
                        @if(isset($newMember) && $newMember->pdf)
                            <div style="margin-top: 8px;">
                                <a href="{{ asset($newMember->pdf) }}" target="_blank" style="color: #2563eb; text-decoration: underline;">
                                    View Current PDF
                                </a>
                            </div>
                        @endif
                        @error('pdf')
                            <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-actions" style="margin-top: 10px; text-align: right;">
                    <button type="submit" class="action-button primary" style="background: #4F8A8B; color: #fff; border: none; border-radius: 6px; padding: 10px 28px; font-size: 1.08em; font-weight: 600; box-shadow: 0 2px 8px rgba(79,138,139,0.08); transition: background 0.2s;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 7px;"></i>
                        Update Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    function showPdfFilename(input) {
        const file = input.files[0];
        const filenameDiv = document.getElementById('pdf-filename');
        if (file) {
            filenameDiv.textContent = file.name;
            filenameDiv.style.display = 'block';
        } else {
            filenameDiv.textContent = '';
            filenameDiv.style.display = 'none';
        }
    }
    function handlePdfDrop(event) {
        const dt = event.dataTransfer;
        const files = dt.files;
        if (files.length > 0 && files[0].type === "application/pdf") {
            const input = document.getElementById('pdf');
            input.files = files;
            showPdfFilename(input);
        }
        document.getElementById('pdf-dropzone').style.borderColor = '#4F8A8B';
    }
</script>
@endsection
