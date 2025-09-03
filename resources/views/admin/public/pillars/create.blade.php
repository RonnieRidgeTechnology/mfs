@extends('layouts.admin')
@section('content')
    <style>
        /* Modern floating label styles */
        .modern-form {
            padding: 30px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            margin: 0 auto;
        }
        .form-row {
            display: flex;
            gap: 24px;
            margin-bottom: 18px;
        }
        .form-group {
            flex: 1;
            position: relative;
            margin-bottom: 18px;
        }
        .form-floating {
            position: relative;
        }
        .form-floating input,
        .form-floating select,
        .form-floating textarea {
            width: 100%;
            padding: 18px 12px 8px 12px;
            font-size: 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            background: transparent;
            outline: none;
            transition: border-color 0.2s;
            resize: none;
        }
        .form-floating input:focus,
        .form-floating select:focus,
        .form-floating textarea:focus {
            border-color: #2563eb;
        }
        .form-floating label {
            position: absolute;
            top: 16px;
            left: 14px;
            color: #6b7280;
            font-size: 1rem;
            pointer-events: none;
            background: #fff;
            padding: 0 4px;
            transition: 0.2s;
        }
        .form-floating input:not(:placeholder-shown) + label,
        .form-floating input:focus + label,
        .form-floating select:focus + label,
        .form-floating select:not([value=""]) + label,
        .form-floating textarea:focus + label,
        .form-floating textarea:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 0.85rem;
            color: #2563eb;
            background: #fff;
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
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        .action-button.primary {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .action-button.primary:hover {
            background: #1d4ed8;
        }
        /* Dropzone style for image input */
        .dropzone {
            border: 2px dashed #2563eb;
            border-radius: 10px;
            background: #f8fafc;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
            text-align: center;
        }
        .dropzone.dragover {
            border-color: #1d4ed8;
            background: #e0e7ff;
        }
        .dropzone input[type="file"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            cursor: pointer;
        }
        .dropzone .dz-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .dropzone img {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
            margin-bottom: 6px;
        }
        .dropzone .dz-remove {
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.95em;
            margin-top: 2px;
        }
        @media (max-width: 700px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
    <main class="main-content">
        @include('layouts.header')

        <div class="content">
            <div class="table-section">
                <div class="table-header">
                    <h2>{{ isset($pillar) && $pillar->id ? 'Edit Pillar of islam' : 'Add New Pillar of islam' }}</h2>
                </div>

                <form
                    method="POST"
                    action="{{ (isset($pillar) && $pillar->id) ? route('five_pillars.update', ['id' => $pillar->id]) : route('five_pillars.store') }}"
                    class="modern-form"
                    enctype="multipart/form-data"
                    novalidate
                >
                    @csrf
                    @if(isset($pillar) && $pillar->id)
                        @method('PUT')
                        <input type="hidden" name="pillar_id" value="{{ $pillar->id }}">
                    @endif

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', isset($pillar) ? $pillar->name : '') }}"
                                   class="{{ $errors->has('name') ? 'is-invalid' : (strlen(old('name', isset($pillar) ? $pillar->name : '')) ? 'is-valid' : '') }}"
                                   required
                                   placeholder="Name">
                            <label for="name">Pillar Name</label>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @elseif(strlen(old('name', isset($pillar) ? $pillar->name : '')))
                                 <div class="valid-feedback">Name looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label style="font-weight: 500; margin-bottom: 6px; display: block;">Image</label>
                            <div class="dropzone" id="pillar-dropzone">
                                <input type="file"
                                       name="image"
                                       id="image"
                                       accept="image/*"
                                       @if(!isset($pillar) || !$pillar->image) required @endif
                                >
                                <div class="dz-message" id="dz-message">
                                    <span>
                                        <span style="font-size:1.2em; color:#2563eb;">&#8682;</span><br>
                                        <span style="color:#6b7280;">Drag & drop or click to upload image</span>
                                    </span>
                                </div>
                                <div class="dz-preview" id="dz-preview" style="display:none;">
                                    <img id="dz-image-preview" src="" alt="Image Preview">
                                    <button type="button" class="dz-remove" id="dz-remove-btn">Remove</button>
                                </div>

                            </div>
                            @if($errors->has('image'))
                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                            @if(isset($pillar) && $pillar->image)
                            <div class="dz-preview" id="dz-existing-preview" style="display:block;">
                                <img src="{{ asset($pillar->image) }}" alt="Current Image" style="width: 100px; height:100px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                                <span style="font-size:0.95em; color:#6b7280;">Current Image</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <select name="is_active" id="is_active"
                                    class="{{ $errors->has('is_active') ? 'is-invalid' : (old('is_active', isset($pillar) ? $pillar->is_active : (isset($pillar) ? $pillar->is_active : '')) !== '' ? 'is-valid' : '') }}"
                                    required>
                                <option value="" disabled {{ old('is_active', isset($pillar) ? $pillar->is_active : (isset($pillar) ? $pillar->is_active : '')) === '' ? 'selected' : '' }}></option>
                                <option value="1" {{ old('is_active', isset($pillar) ? $pillar->is_active : (isset($pillar) ? $pillar->is_active : 1)) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', isset($pillar) ? $pillar->is_active : (isset($pillar) ? $pillar->is_active : 1)) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="is_active">Status</label>
                            @if($errors->has('is_active'))
                                <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                            @elseif(old('is_active', isset($pillar) ? $pillar->is_active : (isset($pillar) ? $pillar->is_active : '')) !== '')
                                <div class="valid-feedback">Status looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('five_pillars.index') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="action-button primary">
                            {{ (isset($pillar) && $pillar->id) ? 'Update Pillar' : 'Add Pillar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        // Dropzone-like image preview and remove
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('pillar-dropzone');
            const fileInput = document.getElementById('image');
            const dzMessage = document.getElementById('dz-message');
            const dzPreview = document.getElementById('dz-preview');
            const dzImagePreview = document.getElementById('dz-image-preview');
            const dzRemoveBtn = document.getElementById('dz-remove-btn');
            const dzExistingPreview = document.getElementById('dz-existing-preview');

            // Drag over effect
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });
            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            });
            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    fileInput.files = e.dataTransfer.files;
                    showPreview(fileInput.files[0]);
                }
            });

            // Click to open file dialog
            dropzone.addEventListener('click', function(e) {
                if (e.target === fileInput || e.target === dzRemoveBtn) return;
                fileInput.click();
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                if (fileInput.files && fileInput.files[0]) {
                    showPreview(fileInput.files[0]);
                }
            });

            // Remove button
            dzRemoveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.value = '';
                dzPreview.style.display = 'none';
                dzMessage.style.display = 'block';
            });

            function showPreview(file) {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    dzImagePreview.src = e.target.result;
                    dzPreview.style.display = 'flex';
                    dzMessage.style.display = 'none';
                    if (dzExistingPreview) dzExistingPreview.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
