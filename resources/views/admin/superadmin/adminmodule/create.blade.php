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
        .form-floating select {
            width: 100%;
            padding: 18px 12px 8px 12px;
            font-size: 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            background: transparent;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-floating input:focus,
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
        .form-floating input:not(:placeholder-shown) + label,
        .form-floating input:focus + label,
        .form-floating select:focus + label,
        .form-floating select:not([value=""]) + label {
            top: -10px;
            left: 10px;
            font-size: 0.85rem;
            color: #2563eb;
            background: #fff;
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
        .profile-image-preview {
            margin-top: 10px;
        }
        .profile-image-preview img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
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
                    <h2>{{ isset($admin) ? 'Edit Admin' : 'Add New Admin' }}</h2>
                </div>

                <form
                    method="POST"
                    action="{{ isset($admin) ? route('admin.update', $admin->id) : route('admin.store') }}"
                    class="modern-form"
                    enctype="multipart/form-data"
                    novalidate
                >
                    @csrf
                    @if(isset($admin))
                        @method('PUT')
                    @endif

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', isset($admin) ? $admin->name : '') }}"
                                   class="{{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}"
                                   required
                                   placeholder="Name">
                            <label for="name">Name</label>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @elseif(old('name'))
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                        <div class="form-group form-floating">
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', isset($admin) ? $admin->email : '') }}"
                                   class="{{ $errors->has('email') ? 'is-invalid' : (old('email') ? 'is-valid' : '') }}"
                                   required
                                   placeholder="Email">
                            <label for="email">Email</label>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @elseif(old('email'))
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone', isset($admin) ? $admin->phone : '') }}"
                                   class="{{ $errors->has('phone') ? 'is-invalid' : (old('phone') ? 'is-valid' : '') }}"
                                   placeholder="Phone">
                            <label for="phone">Phone</label>
                            @if($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @elseif(old('phone'))
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                        <div class="form-group form-floating">
                            <select name="status" id="status"
                                class="{{ $errors->has('status') ? 'is-invalid' : (old('status') !== null ? 'is-valid' : '') }}"
                                required>
                                <option value="" disabled {{ old('status', isset($admin) ? $admin->status : '') === '' ? 'selected' : '' }}></option>
                                <option value="1" {{ old('status', isset($admin) ? $admin->status : 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', isset($admin) ? $admin->status : 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="status">Status</label>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                            @elseif(old('status') !== null)
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-floating">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="{{ $errors->has('password') ? 'is-invalid' : (old('password') ? 'is-valid' : '') }}"
                                   @if(!isset($admin)) required @endif
                                   placeholder="Password">
                            <label for="password">{{ isset($admin) ? 'New Password (optional)' : 'Password' }}</label>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @elseif(old('password'))
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                        <div class="form-group form-floating">
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="{{ $errors->has('password_confirmation') ? 'is-invalid' : (old('password_confirmation') ? 'is-valid' : '') }}"
                                   @if(!isset($admin)) required @endif
                                   placeholder="Confirm Password">
                            <label for="password_confirmation">{{ isset($admin) ? 'Confirm New Password' : 'Confirm Password' }}</label>
                            @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                            @elseif(old('password_confirmation'))
                                <div class="valid-feedback">Looks good!</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group form-floating" style="margin-bottom: 0;">
                        <input type="file"
                               name="profile_image"
                               id="profile_image"
                               class="{{ $errors->has('profile_image') ? 'is-invalid' : '' }}"
                               style="padding-top: 18px;">
                        <label for="profile_image" style="top: -10px; left: 10px; font-size: 0.85rem; color: #2563eb;">Profile Image</label>
                        @if($errors->has('profile_image'))
                            <div class="invalid-feedback">{{ $errors->first('profile_image') }}</div>
                        @elseif(old('profile_image'))
                            <div class="valid-feedback">Looks good!</div>
                        @endif
                        @if(isset($admin) && $admin->profile_image)
                            <div class="profile-image-preview">
                                <img src="{{ asset($admin->profile_image) }}" alt="Profile Image">
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.list') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="action-button primary">
                            {{ isset($admin) ? 'Update Admin' : 'Add Admin' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
