@extends('layouts.admin')
@section('content')
    <main class="main-content">
        @include('layouts.header')

        <div class="content">
            <div class="table-section">
                <div class="table-header">
                    <h2>Profile</h2>
                    <div>
                        <span class="status-badge">
                            @if($user->status == 1)
                                <i class="fas fa-check-circle" style="color: #22c55e; margin-right: 0.3em;"></i> Active
                            @else
                                <i class="fas fa-times-circle" style="color: #ef4444; margin-right: 0.3em;"></i> Inactive
                            @endif
                        </span>
                    </div>
                </div>

                <div class="profile-tabs">
                    <ul class="tab-nav">
                        <li class="tab-item active" data-tab="basic">
                            <i class="fas fa-user" style="margin-right: 0.4em;"></i> Basic Info
                        </li>
                        <li class="tab-item" data-tab="security">
                            <i class="fas fa-lock" style="margin-right: 0.4em;"></i> Security
                        </li>
                    </ul>

                    <!-- Basic Info Tab -->

                    <div class="tab-content active" id="basic">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            id="profileForm" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="profile-form">
                                <div class="profile-image-preview">
                                    <img id="profilePreview"
                                        src="{{ $user->profile_image ? asset($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                        alt="Profile Image">
                                </div>
                                <div class="form-row">
                                    <div class="floating-group" style="width:100%;">
                                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*"
                                            style="padding-top: 1.5rem;" placeholder=" " />
                                        <label for="profileImageInput" style="top:0.2rem;font-size:0.82em;">Profile
                                            Image</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="floating-group" style="flex:1;">
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            required placeholder=" " autocomplete="off">
                                        <label for="name">Name</label>
                                        <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                        <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                        <div class="invalid-feedback">
                                            Please provide your name.
                                        </div>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                    <div class="floating-group" style="flex:1;">
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                            required readonly placeholder=" "
                                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                                        <label for="email">Email</label>
                                        <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                        <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                        <div class="invalid-feedback">
                                            Please provide a valid email.
                                        </div>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="floating-group" style="flex:1;">
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                            placeholder=" ">
                                        <label for="phone">Phone</label>
                                        <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                        <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                        <div class="invalid-feedback">
                                            Please provide a valid phone number.
                                        </div>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                </div>

                                    <div class="floating-group" style="flex:1;">
                                        <input type="text" name="type" id="type" value="{{ old('type', $user->type) }}"
                                            readonly placeholder=" ">
                                        <label for="type">Role</label>
                                        <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                   @if($user->type === 'member')
                               <div class="floating-group" style="flex:1;">
                                    <input type="text" name="member_status" id="member_status" value="{{ old('member_status', $user->member_status ?? 'No Member Status') }}"
                                        readonly placeholder=" ">
                                    <label for="member_status">Member Status</label>
                                    <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div class="floating-group" style="flex:1;">
                                    <input type="text" name="cover" id="cover" value="{{ old('cover', $user->cover ?? 'No Cover') }}"
                                        readonly placeholder=" ">
                                    <label for="cover">Cover</label>
                                    <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                    <div class="alert alert-info" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 12px 16px; border-radius: 5px; margin: 10px 0 18px 0; display: flex; align-items: center; gap: 0.7em;">
                                        <i class="fas fa-info-circle" style="font-size: 1.15em;"></i>
                                        <span>To change your <strong>Member Status</strong> or <strong>Cover</strong>, please contact the administrator.</span>
                                    </div>
                                    @endif
                                <div class="form-actions">
                                    <button type="submit" class="action-button primary">Update Profile</button>
                                </div>

                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-content" id="security">
                        <form method="POST" action="{{ route('profile.password.update') }}" id="securityForm" novalidate>
                            @csrf

                            <div class="profile-form">
                                <!-- Password Requirements Alert -->

                                <div style="display: flex; gap: 32px; align-items: flex-start;">
                                    <div style="flex: 1;">
                                        <div class="floating-group">
                                            <input type="password" name="current_password" id="current_password" required
                                                placeholder=" " autocomplete="current-password"
                                                class="@error('current_password') is-invalid @enderror">
                                            <label for="current_password">Current Password</label>
                                            <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                            <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                            @error('current_password')
                                                <div class="invalid-feedback" style="display:block;">
                                                    {{ $message }}
                                                </div>
                                            @else
                                                <div class="invalid-feedback">
                                                    Please enter your current password.
                                                </div>
                                            @enderror
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="floating-group">
                                            <input type="password" name="password" id="password" required placeholder=" "
                                                autocomplete="new-password">
                                            <label for="password">New Password</label>
                                            <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                            <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                            <div class="invalid-feedback">
                                                Please enter a valid password.
                                            </div>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="floating-group">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                required placeholder=" " autocomplete="new-password">
                                            <label for="password_confirmation">Confirm New Password</label>
                                            <span class="validation-icon invalid"><i class="fas fa-times-circle"></i></span>
                                            <span class="validation-icon valid"><i class="fas fa-check-circle"></i></span>
                                            <div class="invalid-feedback">
                                                Passwords do not match.
                                            </div>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex: 1; min-width: 240px;">
                                        <div class="alert alert-info"
                                            style="background: #e7f3fe; color: #084298; border: 1px solid #b6e0fe; padding: 12px 16px; border-radius: 5px; margin-bottom: 18px;">
                                            <strong>Password Policy & Guidelines:</strong>
                                            <ul style="margin: 8px 0 0 18px; padding: 0; font-size: 0.97em;">
                                                <li>At least 8 characters</li>
                                                <li>At least one uppercase letter (A-Z)</li>
                                                <li>At least one lowercase letter (a-z)</li>
                                                <li>At least one number (0-9)</li>
                                                <li>Can be left blank to keep your current password</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="action-button primary">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
