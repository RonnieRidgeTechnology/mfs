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
                    Web Settings
                </h2>
            </div>


                 <form method="POST" action="{{ route('websettings.update') }}"  enctype="multipart/form-data"  class="add-form" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(79,138,139,0.08); padding: 36px 32px 28px 32px;">
                    @csrf
                    <div class="email-setting-container" style="background: linear-gradient(90deg, #e0f7fa 0%, #f1f8e9 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_member_creation_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Email on Member Creation?
                            </label>
                            <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, a welcome email will be sent to every new member upon account creation. This helps keep your members informed and engaged from the start.
                            </div>
                            <select name="send_member_creation_email" id="send_member_creation_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_member_creation_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_member_creation_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>

                    <div style="background: linear-gradient(90deg, #f1f8e9 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px; width: 100%;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_admin_creation_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Email on Admin Creation?
                            </label>
                            <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, a welcome email will be sent to every new admin upon account creation. This helps keep your admins informed and engaged from the start.
                            </div>
                            <select name="send_admin_creation_email" id="send_admin_creation_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_admin_creation_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_admin_creation_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>
                     <div style="background: linear-gradient(90deg, #fffde7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px; width: 100%;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-money-check-dollar"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_fee_completion_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Email on Fee Completion?
                            </label>
                            <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, an email notification will be sent to members when their fee payment is completed. This helps keep your members informed about their payment status and provides a record of successful transactions.
                            </div>
                            <select name="send_fee_completion_email" id="send_fee_completion_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_fee_completion_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_fee_completion_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>
                    <div style="background: linear-gradient(90deg, #e0f7fa 0%, #fffde7 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px; width: 100%;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_guest_promoted_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Email When Guest is Promoted to Member?
                            </label>
                            <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, an email notification will be sent to a user when their account is promoted from guest to member. This helps keep users informed about their account status changes.
                            </div>
                            <select name="send_guest_promoted_email" id="send_guest_promoted_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_guest_promoted_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_guest_promoted_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>
                     <div style="background: linear-gradient(90deg, #fffde7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px; width: 100%;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_newsletter_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Newsletter Emails?
                            </label>
                            <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, newsletter emails will be sent to members. This helps keep your members updated with the latest news and announcements.
                            </div>
                            <select name="send_newsletter_email" id="send_newsletter_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_newsletter_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_newsletter_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>
                     <div style="background: linear-gradient(90deg, #e0f7fa 0%, #fffde7 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07); display: flex; align-items: flex-start; gap: 18px; width: 100%;">
                        <div style="flex-shrink: 0;">
                            <div style="background: #4F8A8B; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 2em;">
                                <i class="fa-solid fa-envelope-circle-check"></i>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <label for="send_contact_us_email" style="font-weight: 600; font-size: 1.1em; color: #2d3a3a; display: block; margin-bottom: 6px;">
                                Send Email When Contact Us Form is Submitted?
                            </label>
                              <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                                <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                                When enabled, an email notification will be sent to both the admin and the user when the Contact Us form is submitted on the website.
                            </div>
                            <select name="send_contact_us_email" id="send_contact_us_email" required
                                style="width: 180px; padding: 8px 12px; border-radius: 6px; border: 1px solid #b2dfdb; background: #f9fdfc; font-size: 1em; margin-top: 2px;">
                                <option value="1" {{ $setting->send_contact_us_email ? 'selected' : '' }}>Yes, send email</option>
                                <option value="0" {{ !$setting->send_contact_us_email ? 'selected' : '' }}>No, don't send</option>
                            </select>
                        </div>
                    </div>
                    <div style="background: linear-gradient(90deg, #f9fbe7 0%, #e0f7fa 100%); border-radius: 12px; padding: 28px 24px 22px 24px; margin-bottom: 28px; box-shadow: 0 1px 8px rgba(79,138,139,0.07);">
                        <h3 style="font-weight: 700; color: #4F8A8B; margin-bottom: 12px; font-size: 1.18em; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-address-book" style="color: #4F8A8B;"></i>
                            Contact Information for Website
                        </h3>
                        <div style="margin-bottom: 10px; color: #4F8A8B; font-size: 0.98em;">
                            <i class="fa-solid fa-info-circle" style="margin-right: 5px; color: #4F8A8B;"></i>
                            These fields control the contact information displayed on your website. Keeping them up to date ensures your members and visitors can reach you easily.
                        </div>

                        <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px;">
                            {{-- Primary Email --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                                <input type="email"
                                       name="email1"
                                       id="email1"
                                       class="form-control {{ $errors->has('email1') ? 'is-invalid' : (old('email1') ? 'is-valid' : '') }}"
                                       value="{{ old('email1', $setting->email1) }}"
                                       placeholder=" ">
                                <label for="email1">Primary Email</label>
                                @error('email1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('email1'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>

                            {{-- Secondary Email --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                                <input type="email"
                                       name="email2"
                                       id="email2"
                                       class="form-control {{ $errors->has('email2') ? 'is-invalid' : (old('email2') ? 'is-valid' : '') }}"
                                       value="{{ old('email2', $setting->email2) }}"
                                       placeholder=" ">
                                <label for="email2">Secondary Email</label>
                                @error('email2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('email2'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 18px; margin-top: 16px;">
                            {{-- Primary Phone --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                                <input type="text"
                                       name="phone1"
                                       id="phone1"
                                       class="form-control {{ $errors->has('phone1') ? 'is-invalid' : (old('phone1') ? 'is-valid' : '') }}"
                                       value="{{ old('phone1', $setting->phone1) }}"
                                       placeholder=" ">
                                <label for="phone1">Primary Phone</label>
                                @error('phone1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('phone1'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>

                            {{-- Secondary Phone --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px;">
                                <input type="text"
                                       name="phone2"
                                       id="phone2"
                                       class="form-control {{ $errors->has('phone2') ? 'is-invalid' : (old('phone2') ? 'is-valid' : '') }}"
                                       value="{{ old('phone2', $setting->phone2) }}"
                                       placeholder=" ">
                                <label for="phone2">Secondary Phone</label>
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('phone2'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="form-group form-floating" style="margin-top: 16px;">
                            <textarea name="address"
                                      id="address"
                                      rows="3"
                                      class="form-control {{ $errors->has('address') ? 'is-invalid' : (old('address') ? 'is-valid' : '') }}"
                                      placeholder="Address"
                                      style="resize: vertical;">{{ old('address', $setting->address) }}</textarea>
                            <label for="address">Address</label>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('address'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>

                        {{-- Address Link --}}
                        <div class="form-group form-floating" style="margin-top: 16px;">
                            <input type="text"
                                   name="address_link"
                                   id="address_link"
                                   class="form-control {{ $errors->has('address_link') ? 'is-invalid' : (old('address_link') ? 'is-valid' : '') }}"
                                   value="{{ old('address_link', $setting->address_link) }}"
                                   placeholder="Address Link (Google Maps URL)">
                            <label for="address_link">Address Link (Google Maps URL)</label>
                            @error('address_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('address_link'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    {{-- Favicon Icon (Dropzone style with image preview) --}}
                    <div class="form-group" style="margin-top: 16px;">
                        <label for="favicon_icon" style="font-weight: 500;">Favicon Icon</label>
                        <div id="favicon-dropzone" style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; background: #fafbfc;">
                            <input type="file"
                                   name="favicon_icon"
                                   id="favicon_icon"
                                   accept=".jpeg,.png,.jpg,.gif,.svg,.ico,.webp"
                                   style="display: none;">
                            <div id="favicon-preview-container">
                                @if(old('favicon_icon'))
                                    <img src="{{ old('favicon_icon') }}" alt="Favicon Preview" id="favicon-preview" style="max-height: 64px; margin-bottom: 8px;">
                                @elseif($setting->favicon_icon)
                                    <img src="{{ asset($setting->favicon_icon) }}" alt="Favicon Preview" id="favicon-preview" style="max-height: 64px; margin-bottom: 8px;">
                                @else
                                    <img src="https://via.placeholder.com/64x64?text=Favicon" alt="Favicon Preview" id="favicon-preview" style="max-height: 64px; margin-bottom: 8px; opacity: 0.4;">
                                @endif
                            </div>
                            <div id="favicon-dropzone-text" style="color: #6b7280;">
                                <span>Drag &amp; drop favicon here, or <span style="color: #2563eb; text-decoration: underline; cursor: pointer;">browse</span></span>
                                <div style="font-size: 0.9em; color: #9ca3af; margin-top: 4px;">(jpeg, png, jpg, gif, svg, ico, webp, max 10MB)</div>
                            </div>
                        </div>
                        @error('favicon_icon')
                            <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                        @endif
                    </div>

                    </div>
                        {{-- Social Media & Copyright Fields --}}
                        <div class="row" style="margin-top: 16px; gap: 16px;">
                            {{-- Facebook Link --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px; position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #3b5998;">
                                    <i class="fab fa-facebook-f"></i>
                                </span>
                                <input type="text"
                                       name="facebook_link"
                                       id="facebook_link"
                                       class="form-control {{ $errors->has('facebook_link') ? 'is-invalid' : (old('facebook_link') ? 'is-valid' : '') }}"
                                       value="{{ old('facebook_link', $setting->facebook_link) }}"
                                       placeholder=" "
                                       style="padding-left: 2.2em;">
                                <label for="facebook_link">Facebook Link</label>
                                @error('facebook_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('facebook_link'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>

                            {{-- YouTube Link --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px; position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #FF0000;">
                                    <i class="fab fa-youtube"></i>
                                </span>
                                <input type="text"
                                       name="youtube_link"
                                       id="youtube_link"
                                       class="form-control {{ $errors->has('youtube_link') ? 'is-invalid' : (old('youtube_link') ? 'is-valid' : '') }}"
                                       value="{{ old('youtube_link', $setting->youtube_link) }}"
                                       placeholder=" "
                                       style="padding-left: 2.2em;">
                                <label for="youtube_link">YouTube Link</label>
                                @error('youtube_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('youtube_link'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row" style="margin-top: 16px; gap: 16px;">
                            {{-- Instagram Link --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px; position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #C13584;">
                                    <i class="fab fa-instagram"></i>
                                </span>
                                <input type="text"
                                       name="insta_link"
                                       id="insta_link"
                                       class="form-control {{ $errors->has('insta_link') ? 'is-invalid' : (old('insta_link') ? 'is-valid' : '') }}"
                                       value="{{ old('insta_link', $setting->insta_link) }}"
                                       placeholder=" "
                                       style="padding-left: 2.2em;">
                                <label for="insta_link">Instagram Link</label>
                                @error('insta_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('insta_link'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>

                            {{-- LinkedIn Link --}}
                            <div class="form-group form-floating" style="flex: 1 1 220px; min-width: 220px; position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #0077b5;">
                                    <i class="fab fa-linkedin-in"></i>
                                </span>
                                <input type="text"
                                       name="linkdin_link"
                                       id="linkdin_link"
                                       class="form-control {{ $errors->has('linkdin_link') ? 'is-invalid' : (old('linkdin_link') ? 'is-valid' : '') }}"
                                       value="{{ old('linkdin_link', $setting->linkdin_link) }}"
                                       placeholder=" "
                                       style="padding-left: 2.2em;">
                                <label for="linkdin_link">LinkedIn Link</label>
                                @error('linkdin_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @elseif(old('linkdin_link'))
                                    <div class="valid-feedback">Looks good!</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group form-floating" style="margin-top: 16px; position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888;">
                                <i class="fa fa-copyright"></i>
                            </span>
                            <input type="text"
                                   name="copy_right"
                                   id="copy_right"
                                   class="form-control {{ $errors->has('copy_right') ? 'is-invalid' : (old('copy_right') ? 'is-valid' : '') }}"
                                   value="{{ old('copy_right', $setting->copy_right) }}"
                                   placeholder=" "
                                   style="padding-left: 2.2em;">
                            <label for="copy_right">Copyright Text</label>
                            @error('copy_right')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @elseif(old('copy_right'))
                                <div class="valid-feedback">Looks good!</div>
                            @enderror
                        </div>
                    <div class="form-actions" style="margin-top: 10px; text-align: right;">
                        <button type="submit" class="action-button primary" style="background: #4F8A8B; color: #fff; border: none; border-radius: 6px; padding: 10px 28px; font-size: 1.08em; font-weight: 600; box-shadow: 0 2px 8px rgba(79,138,139,0.08); transition: background 0.2s;">
                            <i class="fa-solid fa-floppy-disk" style="margin-right: 7px;"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('favicon-dropzone');
        const input = document.getElementById('favicon_icon');
        const preview = document.getElementById('favicon-preview');
        const dropzoneText = document.getElementById('favicon-dropzone-text');

        // Allowed mime types for favicon
        const allowedTypes = [
            'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/webp'
        ];

        // Click to open file dialog
        dropzone.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                input.click();
            }
        });

        // Drag & drop
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.style.background = '#e0e7ef';
        });
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.style.background = '#fafbfc';
        });
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.style.background = '#fafbfc';
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                if (!allowedTypes.includes(file.type)) {
                    showFaviconError('The favicon icon must be a file of type: jpeg, png, jpg, gif, svg, ico, webp.');
                    input.value = '';
                    preview.src = '';
                    preview.style.opacity = 0;
                    return;
                }
                // Set the file to the input (for form submission)
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                showFaviconPreview(file);
            }
        });

        // File input change
        input.addEventListener('change', function() {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (!allowedTypes.includes(file.type)) {
                    showFaviconError('The favicon icon must be a file of type: jpeg, png, jpg, gif, svg, ico, webp.');
                    input.value = '';
                    preview.src = '';
                    preview.style.opacity = 0;
                    return;
                }
                showFaviconPreview(file);
            }
        });

        function showFaviconPreview(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.opacity = 1;
            };
            reader.readAsDataURL(file);
            clearFaviconError();
        }

        function showFaviconError(message) {
            let errorDiv = document.getElementById('favicon-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'favicon-error';
                errorDiv.className = 'invalid-feedback';
                errorDiv.style.display = 'block';
                dropzone.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
            dropzone.classList.add('is-invalid');
        }

        function clearFaviconError() {
            let errorDiv = document.getElementById('favicon-error');
            if (errorDiv) {
                errorDiv.remove();
            }
            dropzone.classList.remove('is-invalid');
        }
    });
</script>
@endsection
