@extends('layouts.admin')
@section('content')
    <style>
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

        /* Sleek/modern style for points */
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
            box-shadow: 0 1px 4px 0 rgba(37,99,235,0.04);
            transition: box-shadow 0.18s, background 0.18s;
            position: relative;
        }

        .point-row:focus-within {
            background: #e8edfa;
            box-shadow: 0 2px 8px 0 rgba(37,99,235,0.10);
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

        .remove-point-btn:hover, .remove-point-btn:focus {
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
            box-shadow: 0 1px 4px 0 rgba(37,99,235,0.08);
            transition: background 0.18s, box-shadow 0.18s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-point-btn:hover, .add-point-btn:focus {
            background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
            box-shadow: 0 2px 8px 0 rgba(37,99,235,0.13);
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
                <div class="table-header">
                    <h2>{{ isset($rule) ? 'Edit Rule & Regulation' : 'Add New Rule & Regulation' }}</h2>
                </div>

                <form method="POST" action="{{ isset($rule) ? route('rules.update', $rule->id) : route('rules.store') }}"
                    class="modern-form" novalidate id="rule-form">
                    @csrf
                    @if(isset($rule))
                        @method('PUT')
                        <input type="hidden" name="rule_id" value="{{ $rule->id }}">
                    @endif

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text" name="title" id="title"
                                value="{{ old('title', isset($rule) ? $rule->title : '') }}"
                                class="{{ $errors->has('title') ? 'is-invalid' : (strlen(old('title', isset($rule) ? $rule->title : '')) ? 'is-valid' : '') }}"
                                required placeholder="Title">
                            <label for="title">Title</label>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                            @elseif(strlen(old('title', isset($rule) ? $rule->title : '')))
                                <div class="valid-feedback">Title looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="width:100%;">
                            <label
                                style="font-weight: 500; color: #374151; margin-bottom: 6px; display:block;">Points</label>
                            <ul class="points-list" id="points-list">
                                @php
                                    $oldPoints = old('points', isset($rule) ? $rule->points : ['']);
                                    if (empty($oldPoints) || !is_array($oldPoints))
                                        $oldPoints = [''];
                                @endphp
                                @foreach($oldPoints as $i => $point)
                                    <li class="point-row">
                                        <input type="text" name="points[]" value="{{ $point }}"
                                            class="point-input {{ $errors->has('points.' . $i) ? 'is-invalid' : (strlen($point) ? 'is-valid' : '') }}"
                                            required placeholder="Enter point...">
                                        <button type="button" class="remove-point-btn" onclick="removePoint(this)"
                                            title="Remove point" aria-label="Remove point">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            @if($errors->has('points'))
                                <div class="invalid-feedback">{{ $errors->first('points') }}</div>
                            @endif
                            @foreach($errors->get('points.*') as $pointErrors)
                                @foreach($pointErrors as $pointError)
                                    <div class="invalid-feedback">{{ $pointError }}</div>
                                @endforeach
                            @endforeach
                            <button type="button" class="add-point-btn" onclick="addPoint()">
                                <i class="fa fa-plus"></i>
                                <span>Add Point</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <select name="is_active" id="is_active"
                                class="{{ $errors->has('is_active') ? 'is-invalid' : (strlen((string)old('is_active', isset($rule) ? $rule->is_active : '')) ? 'is-valid' : '') }}"
                                required
                                style="background: transparent;"
                                {{ isset($rule) ? '' : '' }}
                                >
                                <option value="" disabled {{ old('is_active', isset($rule) ? $rule->is_active : '') === '' ? 'selected' : '' }}></option>
                                <option value="1" {{ old('is_active', isset($rule) ? $rule->is_active : 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', isset($rule) ? $rule->is_active : 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="is_active">Status</label>
                            @if($errors->has('is_active'))
                                <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                            @elseif(strlen((string)old('is_active', isset($rule) ? $rule->is_active : '')))
                                <div class="valid-feedback">Status looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('rules.index') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="action-button primary">
                            {{ isset($rule) ? 'Update Rule' : 'Add Rule' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        function addPoint() {
            const list = document.getElementById('points-list');
            const li = document.createElement('li');
            li.className = 'point-row';
            li.innerHTML = `
                <input type="text" name="points[]" class="point-input" required placeholder="Enter point...">
                <button type="button" class="remove-point-btn" onclick="removePoint(this)" title="Remove point" aria-label="Remove point">
                    <i class="fa fa-minus"></i>
                </button>
            `;
            list.appendChild(li);
            // Focus the new input for better UX
            setTimeout(() => {
                li.querySelector('input').focus();
            }, 10);
        }
        function removePoint(btn) {
            const list = document.getElementById('points-list');
            if (list.children.length > 1) {
                btn.closest('.point-row').remove();
            } else {
                // If only one left, just clear its value
                btn.closest('.point-row').querySelector('input').value = '';
            }
        }
    </script>
@endsection