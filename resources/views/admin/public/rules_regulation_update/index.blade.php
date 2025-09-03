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
    </style>
    <main class="main-content">
        @include('layouts.header')

        <div class="content">
            <div class="table-section">
                <div
                    style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                    <h3
                        style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-gavel" style="color: #4F8A8B;"></i>
                        Rules & Regulation Update
                    </h3>
                    <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                        <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                        Update the meta information for the Rules & Regulation public page.
                    </div>
                </div>
                <form method="POST" action="{{ route('rules_regulation_update.update') }}" class="modern-form" novalidate
                    id="rules-regulation-update-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="meta_title" id="meta_title"
                                value="{{ old('meta_title', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_title : '') }}"
                                class="{{ $errors->has('meta_title') ? 'is-invalid' : (strlen(old('meta_title', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_title : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Title">
                            <label for="meta_title">Meta Title</label>
                            @if($errors->has('meta_title'))
                                <div class="invalid-feedback">{{ $errors->first('meta_title') }}</div>
                            @elseif(strlen(old('meta_title', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_title : '')))
                                <div class="valid-feedback">Meta Title looks good!</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <textarea name="meta_desc" id="meta_desc"
                                class="{{ $errors->has('meta_desc') ? 'is-invalid' : (strlen(old('meta_desc', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_desc : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Description"
                                style="height: 100px;">{{ old('meta_desc', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_desc : '') }}</textarea>
                            <label for="meta_desc">Meta Description</label>
                            @if($errors->has('meta_desc'))
                                <div class="invalid-feedback">{{ $errors->first('meta_desc') }}</div>
                            @elseif(strlen(old('meta_desc', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_desc : '')))
                                <div class="valid-feedback">Meta Description looks good!</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="meta_keyword" id="meta_keyword"
                                value="{{ old('meta_keyword', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_keyword : '') }}"
                                class="{{ $errors->has('meta_keyword') ? 'is-invalid' : (strlen(old('meta_keyword', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_keyword : '')) ? 'is-valid' : '') }}"
                                placeholder="Meta Keywords">
                            <label for="meta_keyword">Meta Keywords</label>
                            @if($errors->has('meta_keyword'))
                                <div class="invalid-feedback">{{ $errors->first('meta_keyword') }}</div>
                            @elseif(strlen(old('meta_keyword', isset($rulesRegulationUpdate) ? $rulesRegulationUpdate->meta_keyword : '')))
                                <div class="valid-feedback">Meta Keywords look good!</div>
                            @endif
                        </div>
                    </div>
                    {{-- Add more fields here if needed --}}
                    <div class="form-actions">
                        <button type="submit" class="action-button primary">
                            <i class="fa fa-save" style="margin-right: 8px;"></i>
                            Update Rules & Regulation Meta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
