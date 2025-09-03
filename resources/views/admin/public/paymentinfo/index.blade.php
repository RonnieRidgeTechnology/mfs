@extends('layouts.admin')
@section('content')
    <style>
        /* ... keep all styles unchanged ... */
        .modern-form {
            padding: 32px 28px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
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
        .form-floating textarea,
        .form-floating select {
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
        .form-floating textarea:focus,
        .form-floating select:focus {
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

        .form-floating input:not(:placeholder-shown)+label,
        .form-floating input:focus+label,
        .form-floating textarea:focus+label,
        .form-floating textarea:not(:placeholder-shown)+label,
        .form-floating select:focus+label,
        .form-floating select:not([value=""])+label {
            top: -10px;
            left: 10px;
            font-size: 0.85rem;
            color: #2563eb;
            background: #fff;
        }

        .form-floating input.is-invalid,
        .form-floating textarea.is-invalid,
        .form-floating select.is-invalid {
            border-color: #dc2626;
        }

        .form-floating input.is-valid,
        .form-floating textarea.is-valid,
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

        .points-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .point-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            background: #f4f6fb;
            border-radius: 10px;
            padding: 0 0 0 0;
            box-shadow: 0 1px 4px 0 rgba(37, 99, 235, 0.04);
            transition: box-shadow 0.18s, background 0.18s;
            position: relative;
        }

        .point-row:focus-within {
            background: #e8edfa;
            box-shadow: 0 2px 8px 0 rgba(37, 99, 235, 0.10);
        }

        .point-row input[type="text"] {
            flex: 1;
            padding: 18px 16px 10px 16px;
            border-radius: 10px 0 0 10px;
            border: none;
            font-size: 1rem;
            background: transparent;
            outline: none;
            box-shadow: none;
            transition: background 0.18s;
            color: #22223b;
        }

        .point-row input[type="text"]:focus {
            background: #fff;
        }

        .point-row input[type="text"].is-invalid {
            border: none;
            box-shadow: 0 0 0 2px #dc2626 inset;
        }

        .point-row input[type="text"].is-valid {
            border: none;
            box-shadow: 0 0 0 2px #16a34a inset;
        }

        .remove-point-btn {
            background: none;
            color: #a1a1aa;
            border: none;
            border-radius: 0 10px 10px 0;
            padding: 0 18px;
            font-size: 1.25em;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.18s, background 0.18s;
            cursor: pointer;
            outline: none;
        }

        .remove-point-btn:hover,
        .remove-point-btn:focus {
            color: #dc2626;
            background: #fef2f2;
        }

        .remove-point-btn:active {
            background: #fee2e2;
        }

        .add-point-btn {
            background: linear-gradient(90deg, #2563eb 60%, #1d4ed8 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 1px 4px 0 rgba(37, 99, 235, 0.08);
            transition: background 0.18s, box-shadow 0.18s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-point-btn:hover,
        .add-point-btn:focus {
            background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
            box-shadow: 0 2px 8px 0 rgba(37, 99, 235, 0.13);
        }

        .point-row .fa-minus {
            pointer-events: none;
        }

        @media (max-width: 700px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .modern-form {
                padding: 18px 6px;
            }

            .point-row {
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 0;
            }

            .point-row input[type="text"] {
                border-radius: 10px 10px 0 0;
                padding: 16px 12px 10px 12px;
            }

            .remove-point-btn {
                border-radius: 0 0 10px 10px;
                padding: 10px 0;
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
    <main class="main-content">
        @include('layouts.header')

        <div class="content">
            <div class="table-section">
                <div
                    style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3
                        style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-credit-card" style="color: #4F8A8B;"></i>
                        {{ isset($paymentInfo) ? 'Edit Payment Info Sections' : 'Add Payment Info Sections' }}
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Please keep the Payment Info details current for members and visitors.
                    </div>
                </div>

                 <form method="POST" action="{{ route('payment_info.update') }}" class="modern-form" novalidate id="paymentinfo-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="meta_title" id="meta_title"
                                value="{{ old('meta_title', isset($paymentInfo) ? $paymentInfo->meta_title : '') }}"
                                class="{{ $errors->has('meta_title') ? 'is-invalid' : (strlen(old('meta_title', isset($paymentInfo) ? $paymentInfo->meta_title : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Title">
                            <label for="meta_title">Meta Title</label>
                            @if($errors->has('meta_title'))
                                <div class="invalid-feedback">{{ $errors->first('meta_title') }}</div>
                            @elseif(strlen(old('meta_title', isset($paymentInfo) ? $paymentInfo->meta_title : '')))
                                <div class="valid-feedback">Meta Title looks good!</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <textarea name="meta_description" id="meta_description"
                                class="{{ $errors->has('meta_description') ? 'is-invalid' : (strlen(old('meta_description', isset($paymentInfo) ? $paymentInfo->meta_description : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Description"
                                style="min-height: 80px;">{{ old('meta_description', isset($paymentInfo) ? $paymentInfo->meta_description : '') }}</textarea>
                            <label for="meta_description">Meta Description</label>
                            @if($errors->has('meta_description'))
                                <div class="invalid-feedback">{{ $errors->first('meta_description') }}</div>
                            @elseif(strlen(old('meta_description', isset($paymentInfo) ? $paymentInfo->meta_description : '')))
                                <div class="valid-feedback">Meta Description looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="meta_keywords" id="meta_keywords"
                                value="{{ old('meta_keywords', isset($paymentInfo) ? $paymentInfo->meta_keywords : '') }}"
                                class="{{ $errors->has('meta_keywords') ? 'is-invalid' : (strlen(old('meta_keywords', isset($paymentInfo) ? $paymentInfo->meta_keywords : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Keywords">
                            <label for="meta_keywords">Meta Keywords</label>
                            @if($errors->has('meta_keywords'))
                                <div class="invalid-feedback">{{ $errors->first('meta_keywords') }}</div>
                            @elseif(strlen(old('meta_keywords', isset($paymentInfo) ? $paymentInfo->meta_keywords : '')))
                                <div class="valid-feedback">Meta Keywords look good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="note" style="font-weight: 500; color: #374151; margin-bottom: 6px; display:block;">Note</label>
                            <textarea id="note" name="note" class="form-control {{ $errors->has('note') ? 'is-invalid' : (strlen(old('note', isset($paymentInfo) ? $paymentInfo->note : '')) ? 'is-valid' : '') }}" placeholder="Enter note..." style="min-height: 80px;">{{ old('note', isset($paymentInfo) ? $paymentInfo->note : '') }}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('note') }}</div>
                            @elseif(strlen(old('note', isset($paymentInfo) ? $paymentInfo->note : '')))
                                <div class="valid-feedback" style="display:block;">Note looks good!</div>
                            @endif
                        </div>
                    </div>
                    @php
                        // Prepare old input or model data for sections
                        $oldTitles = old('title', isset($paymentInfo) && is_array($paymentInfo->title) ? $paymentInfo->title : []);
                        $oldPoints = old('points', isset($paymentInfo) && is_array($paymentInfo->points) ? $paymentInfo->points : []);
                        $sectionCount = max(count($oldTitles), count($oldPoints), 1);
                        // Ensure at least one section
                        if ($sectionCount < 1)
                            $sectionCount = 1;
                    @endphp

                    <div id="sections-container">
                        @for($s = 0; $s < $sectionCount; $s++)
                            <div class="section-block"
                                style="margin-bottom: 32px; border:1px solid #e5e7eb; border-radius:12px; padding:18px 14px;">
                                <div class="form-row">
                                    <div class="form-group form-floating" style="width:100%;">
                                        <input type="text" name="title[]"
                                            value="{{ old('title.' . $s, isset($oldTitles[$s]) ? $oldTitles[$s] : '') }}"
                                            class="{{ $errors->has('title.' . $s) ? 'is-invalid' : (strlen(old('title.' . $s, isset($oldTitles[$s]) ? $oldTitles[$s] : '')) ? 'is-valid' : '') }}"
                                            required placeholder="Section Title">
                                        <label>Section Title</label>
                                        @if($errors->has('title.' . $s))
                                            <div class="invalid-feedback">{{ $errors->first('title.' . $s) }}</div>
                                        @elseif(strlen(old('title.' . $s, isset($oldTitles[$s]) ? $oldTitles[$s] : '')))
                                            <div class="valid-feedback">Section Title looks good!</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group" style="width:100%;">
                                        <label
                                            style="font-weight: 500; color: #374151; margin-bottom: 6px; display:block;">Points</label>
                                        <ul class="points-list" id="points-list-{{ $s }}">
                                            @php
                                                $pointsArr = isset($oldPoints[$s]) && is_array($oldPoints[$s]) ? $oldPoints[$s] : [''];
                                            @endphp
                                            @foreach($pointsArr as $i => $point)
                                                <li class="point-row">
                                                    <input type="text" name="points[{{ $s }}][]" value="{{ $point }}"
                                                        class="point-input
                                                        @if($errors->has('points.' . $s . '.' . $i))
                                                            is-invalid
                                                        @elseif($i === 0 && isset($point) && is_string($point) && strlen($point))
                                                            is-valid
                                                        @elseif($i !== 0 && strlen($point))
                                                            is-valid
                                                        @endif"
                                                        placeholder="Enter point...">
                                                    <button type="button" class="remove-point-btn"
                                                        onclick="removePoint(this, {{ $s }})" title="Remove point"
                                                        aria-label="Remove point">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if($errors->has('points.' . $s))
                                            <div class="invalid-feedback">{{ $errors->first('points.' . $s) }}</div>
                                        @endif
                                        {{-- Show error for points.0 must be a string --}}
                                        @if($errors->has('points.' . $s . '.0'))
                                            @foreach($errors->get('points.' . $s . '.0') as $pointError)
                                                <div class="invalid-feedback">{{ $pointError }}</div>
                                            @endforeach
                                        @endif
                                        @foreach($errors->get('points.' . $s . '.*') as $pointKey => $pointErrors)
                                            @if($pointKey !== '0')
                                                @foreach($pointErrors as $pointError)
                                                    <div class="invalid-feedback">{{ $pointError }}</div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <button type="button" class="add-point-btn" onclick="addPoint({{ $s }})">
                                            <i class="fa fa-plus"></i>
                                            <span>Add Point</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-actions" style="justify-content: flex-start; margin-top: 0;">
                                    <button type="button" class="btn-cancel" onclick="removeSection(this)"
                                        style="background:#fee2e2; color:#dc2626;">
                                        Remove Section
                                    </button>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="form-row">
                        <button type="button" class="add-point-btn" style="width:100%;justify-content:center;"
                            onclick="addSection()">
                            <i class="fa fa-plus"></i>
                            <span>Add Section</span>
                        </button>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="action-button primary">
                            <i class="fa fa-save" style="margin-right: 8px;"></i>
                            {{ isset($paymentInfo) ? 'Update Payment Info Sections' : 'Save Payment Info Sections' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        // Add a new point input to a section
        function addPoint(sectionIndex) {
            const list = document.getElementById('points-list-' + sectionIndex);
            const li = document.createElement('li');
            li.className = 'point-row';
            li.innerHTML = `
                    <input type="text" name="points[${sectionIndex}][]" class="point-input" placeholder="Enter point...">
                    <button type="button" class="remove-point-btn" onclick="removePoint(this, ${sectionIndex})" title="Remove point" aria-label="Remove point">
                        <i class="fa fa-minus"></i>
                    </button>
                `;
            list.appendChild(li);
            setTimeout(() => {
                li.querySelector('input').focus();
            }, 10);
        }
        // Remove a point input from a section
        function removePoint(btn, sectionIndex) {
            const list = document.getElementById('points-list-' + sectionIndex);
            if (list.children.length > 1) {
                btn.closest('.point-row').remove();
            } else {
                btn.closest('.point-row').querySelector('input').value = '';
            }
        }
        // Add a new section (with title and points)
        function addSection() {
            const container = document.getElementById('sections-container');
            const sectionIndex = container.children.length;
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'section-block';
            sectionDiv.style.marginBottom = '32px';
            sectionDiv.style.border = '1px solid #e5e7eb';
            sectionDiv.style.borderRadius = '12px';
            sectionDiv.style.padding = '18px 14px';
            sectionDiv.innerHTML = `
                    <div class="form-row">
                        <div class="form-group form-floating" style="width:100%;">
                            <input type="text" name="title[]" class="form-control" required placeholder="Section Title">
                            <label>Section Title</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="width:100%;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 6px; display:block;">Points</label>
                            <ul class="points-list" id="points-list-${sectionIndex}">
                                <li class="point-row">
                                    <input type="text" name="points[${sectionIndex}][]" class="point-input" placeholder="Enter point...">
                                    <button type="button" class="remove-point-btn" onclick="removePoint(this, ${sectionIndex})" title="Remove point" aria-label="Remove point">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </li>
                            </ul>
                            <button type="button" class="add-point-btn" onclick="addPoint(${sectionIndex})">
                                <i class="fa fa-plus"></i>
                                <span>Add Point</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-actions" style="justify-content: flex-start; margin-top: 0;">
                        <button type="button" class="btn-cancel" onclick="removeSection(this)" style="background:#fee2e2; color:#dc2626;">
                            Remove Section
                        </button>
                    </div>
                `;
            container.appendChild(sectionDiv);
            // Focus the new section's title
            setTimeout(() => {
                sectionDiv.querySelector('input[name="title[]"]').focus();
            }, 10);
        }
        // Remove a section block
        function removeSection(btn) {
            const container = document.getElementById('sections-container');
            if (container.children.length > 1) {
                btn.closest('.section-block').remove();
            } else {
                // If only one section left, clear its fields
                const section = btn.closest('.section-block');
                section.querySelector('input[name="title[]"]').value = '';
                const points = section.querySelectorAll('.point-input');
                points.forEach(input => input.value = '');
            }
        }
    </script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.CKEDITOR) {
                CKEDITOR.replace('note', {
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
@endsection
