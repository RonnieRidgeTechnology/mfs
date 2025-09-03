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
                    <h2>{{ isset($faq) ? 'Edit FAQ' : 'Add New FAQ' }}</h2>
                </div>

                <form
                    method="POST"
                    action="{{ isset($faq) ? route('faqs.update', $faq->id) : route('faqs.store') }}"
                    class="modern-form"
                    novalidate
                >
                    @csrf
                    @if(isset($faq))
                        @method('PUT')
                        <input type="hidden" name="faq_id" value="{{ $faq->id }}">
                    @endif

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text"
                                   name="question"
                                   id="question"
                                   value="{{ old('question', isset($faq) ? $faq->question : '') }}"
                                   class="{{ $errors->has('question') ? 'is-invalid' : (strlen(old('question', isset($faq) ? $faq->question : '')) ? 'is-valid' : '') }}"
                                   required
                                   placeholder="Question">
                            <label for="question">Question</label>
                            @if($errors->has('question'))
                                <div class="invalid-feedback">{{ $errors->first('question') }}</div>
                            @elseif(strlen(old('question', isset($faq) ? $faq->question : '')))
                                 <div class="valid-feedback">Question looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <textarea
                                name="answer"
                                id="answer"
                                rows="5"
                                class="{{ $errors->has('answer') ? 'is-invalid' : (strlen(old('answer', isset($faq) ? $faq->answer : '')) ? 'is-valid' : '') }}"
                                required
                                placeholder="Answer"
                                style="min-height: 120px;"
                            >{{ old('answer', isset($faq) ? $faq->answer : '') }}</textarea>
                            <label for="answer">Answer</label>
                            @if($errors->has('answer'))
                                <div class="invalid-feedback">{{ $errors->first('answer') }}</div>
                            @elseif(strlen(old('answer', isset($faq) ? $faq->answer : '')))
                                <div class="valid-feedback">Awnser Looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <select name="is_active" id="is_active"
                                    class="{{ $errors->has('is_active') ? 'is-invalid' : (old('is_active', isset($faq) ? $faq->is_active : (isset($faq) ? $faq->is_active : '')) !== '' ? 'is-valid' : '') }}"
                                    required>
                                <option value="" disabled {{ old('is_active', isset($faq) ? $faq->is_active : (isset($faq) ? $faq->is_active : '')) === '' ? 'selected' : '' }}></option>
                                <option value="1" {{ old('is_active', isset($faq) ? $faq->is_active : (isset($faq) ? $faq->is_active : 1)) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', isset($faq) ? $faq->is_active : (isset($faq) ? $faq->is_active : 1)) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="is_active">Status</label>
                            @if($errors->has('is_active'))
                                <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                            @elseif(old('is_active', isset($faq) ? $faq->is_active : (isset($faq) ? $faq->is_active : '')) !== '')
                                <div class="valid-feedback">Status Looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('faqs.index') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="action-button primary">
                            {{ isset($faq) ? 'Update FAQ' : 'Add FAQ' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
