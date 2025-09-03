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
                    <i class="fa-solid fa-gear" style="color: #4F8A8B; font-size: 1.3em;"></i>
                    HMBC Settings
                </h2>
            </div>

            <form method="POST" action="{{ route('hmbc.update', ['id' => $hmbc->id ?? null]) }}" class="add-form" enctype="multipart/form-data" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(79,138,139,0.08); padding: 36px 32px 28px 32px;">
                @csrf

                <div style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3 style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-address-book" style="color: #4F8A8B;"></i>
                        HMBC Information
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Please keep the HMBC details current for members and visitors.
                    </div>

                    {{-- Title --}}
                    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px;">
                        <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : (old('title', $hmbc->title ?? '') ? 'is-valid' : '') }}"
                                   value="{{ old('title', $hmbc->title ?? '') }}"
                                   placeholder=" ">
                            <label for="title">Title</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('title', $hmbc->title ?? ''))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Description with CKEditor --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="desc" style="margin-bottom: 6px; font-weight: 500;">Description</label>
                        <textarea id="desc" name="desc" class="form-control">{{ old('desc', $hmbc->desc ?? '') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                        @elseif(old('desc', $hmbc->desc ?? ''))
                            <div class="valid-feedback" style="display:block;">Looks good!</div>
                        @enderror
                    </div>


                    {{-- Location Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="location_title"
                               id="location_title"
                               class="form-control {{ $errors->has('location_title') ? 'is-invalid' : (old('location_title', $hmbc->location_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('location_title', $hmbc->location_title ?? '') }}"
                               placeholder=" ">
                        <label for="location_title">Location Title</label>
                        @error('location_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('location_title', $hmbc->location_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Location Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="location_desc"
                                  id="location_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('location_desc') ? 'is-invalid' : (old('location_desc', $hmbc->location_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Location Description"
                                  style="resize: vertical;">{{ old('location_desc', $hmbc->location_desc ?? '') }}</textarea>
                        <label for="location_desc">Location Description</label>
                        @error('location_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('location_desc', $hmbc->location_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Location Link --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="location_link"
                               id="location_link"
                               class="form-control {{ $errors->has('location_link') ? 'is-invalid' : (old('location_link', $hmbc->location_link ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('location_link', $hmbc->location_link ?? '') }}"
                               placeholder="Location Link">
                        <label for="location_link">Location Link</label>
                        @error('location_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('location_link', $hmbc->location_link ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Member Title --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="member_title"
                               id="member_title"
                               class="form-control {{ $errors->has('member_title') ? 'is-invalid' : (old('member_title', $hmbc->member_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('member_title', $hmbc->member_title ?? '') }}"
                               placeholder=" ">
                        <label for="member_title">Member Title</label>
                        @error('member_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('member_title', $hmbc->member_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Member Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="member_desc"
                                  id="member_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('member_desc') ? 'is-invalid' : (old('member_desc', $hmbc->member_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Member Description"
                                  style="resize: vertical;">{{ old('member_desc', $hmbc->member_desc ?? '') }}</textarea>
                        <label for="member_desc">Member Description</label>
                        @error('member_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('member_desc', $hmbc->member_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>
                    {{-- Meta Title --}}
                     <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_title"
                               id="meta_title"
                               class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : (old('meta_title', $hmbc->meta_title ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_title', $hmbc->meta_title ?? '') }}"
                               placeholder=" ">
                        <label for="meta_title">Meta Title</label>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_title', $hmbc->meta_title ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Description --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <textarea name="meta_desc"
                                  id="meta_desc"
                                  rows="2"
                                  class="form-control {{ $errors->has('meta_desc') ? 'is-invalid' : (old('meta_desc', $hmbc->meta_desc ?? '') ? 'is-valid' : '') }}"
                                  placeholder="Meta Description"
                                  style="resize: vertical;">{{ old('meta_desc', $hmbc->meta_desc ?? '') }}</textarea>
                        <label for="meta_desc">Meta Description</label>
                        @error('meta_desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_desc', $hmbc->meta_desc ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- Meta Keyword --}}
                    <div class="form-group form-floating" style="margin-top: 16px;">
                        <input type="text"
                               name="meta_keyword"
                               id="meta_keyword"
                               class="form-control {{ $errors->has('meta_keyword') ? 'is-invalid' : (old('meta_keyword', $hmbc->meta_keyword ?? '') ? 'is-valid' : '') }}"
                               value="{{ old('meta_keyword', $hmbc->meta_keyword ?? '') }}"
                               placeholder=" ">
                        <label for="meta_keyword">Meta Keyword</label>
                        @error('meta_keyword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @elseif(old('meta_keyword', $hmbc->meta_keyword ?? ''))
                            <div class="valid-feedback">Looks good!</div>
                        @enderror
                    </div>

                    {{-- PDF Upload --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="pdf" style="font-weight: 500; color: #4F8A8B; margin-bottom: 8px; display: block;">PDF (optional, max 20MB)</label>
                        <div id="pdf-dropzone" style="border: 2px dashed #2563eb; border-radius: 10px; padding: 32px 18px; text-align: center; background: #f8fafc; cursor: pointer; position: relative;">
                            <input type="file"
                                   name="pdf"
                                   id="pdf"
                                   class="form-control {{ $errors->has('pdf') ? 'is-invalid' : '' }}"
                                   accept="application/pdf"
                                   style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;z-index:2;">
                            <div style="pointer-events: none; z-index:1; position:relative;">
                                <i class="fa-solid fa-file-pdf" style="font-size: 2.5em; color: #e63946; margin-bottom: 8px;"></i>
                                <div style="font-size: 1.05em; color: #2563eb; margin-bottom: 4px;">Drag &amp; drop a PDF here, or click to select</div>
                                <div id="pdf-filename" style="color: #4F8A8B; font-size: 0.98em; margin-top: 6px;"></div>
                            </div>
                        </div>
                        @if(isset($hmbc->pdf) && $hmbc->pdf)
                            <div style="margin-top: 6px;">
                                <a href="{{ asset($hmbc->pdf) }}" target="_blank" style="color: #2563eb; text-decoration: underline;">
                                    View current PDF
                                </a>
                            </div>
                        @endif
                        @error('pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 10px; text-align: right;">
                    <button type="submit" class="action-button primary" style="background: #4F8A8B; color: #fff; border: none; border-radius: 6px; padding: 10px 28px; font-size: 1.08em; font-weight: 600; box-shadow: 0 2px 8px rgba(79,138,139,0.08); transition: background 0.2s;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 7px;"></i>
                        Save HMBC Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Flora Editor CDN -->
 <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.CKEDITOR) {
        CKEDITOR.replace('desc', {
            height: 120,
            removeButtons: '',
            toolbar: [
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'document', items: [ 'Source' ] }
            ]
        });
    }
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const descTextarea = document.getElementById('desc');

        // Wait for FloraEditor to load and initialize
        if (typeof FloraEditor !== 'undefined' && descTextarea) {
            initializeEditor();
        } else {
            // Retry every 100ms until FloraEditor is available
            const checkAndInit = setInterval(() => {
                if (typeof FloraEditor !== 'undefined' && descTextarea) {
                    clearInterval(checkAndInit);
                    initializeEditor();
                }
            }, 100);
        }

        function initializeEditor() {
            FloraEditor.create('#desc', {
                height: 180,
                toolbar: [
                    'bold', 'italic', 'underline', 'strike', '|',
                    'ul', 'ol', '|',
                    'link', 'image', '|',
                    'undo', 'redo', 'removeFormat'
                ],
                placeholder: 'Write a description...'
            });
        }
    });

    // PDF Dropzone functionality
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('pdf-dropzone');
        const input = document.getElementById('pdf');
        const filename = document.getElementById('pdf-filename');

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.style.background = '#e3f2fd';
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropzone.style.background = '#f8fafc';
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.background = '#f8fafc';
            if (e.dataTransfer.files.length > 0) {
                input.files = e.dataTransfer.files;
                filename.textContent = e.dataTransfer.files[0].name;
            }
        });

        input.addEventListener('change', () => {
            if (input.files.length > 0) {
                filename.textContent = input.files[0].name;
            } else {
                filename.textContent = '';
            }
        });
    });
</script>
@endsection
