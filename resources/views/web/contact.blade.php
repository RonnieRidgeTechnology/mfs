@extends('layouts.website')
@section('meta_title', isset($contact->meta_title) ? $contact->meta_title : '')
@section('meta_description', isset($contact->meta_desc) ? $contact->meta_desc : '')
@section('meta_keywords', isset($contact->meta_keyword) ? $contact->meta_keyword : '')
@section('content')
    <div class="header-main">
        <div class="hero-banner5"></div>
        @include('layouts.navbar');
        <div class="hero-content1">
            <h1>contact</h1>
        </div>
    </div>
    <!-- .contact-seaction1-start -->
    <div class="contact-seaction1-main">
        <div class="contact-seaction1-main">
            <div class="contact-seaction1-card-main">
                <div class="contact-seaction1-card1">
                    <div class="contact-seaction1-icon">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path
                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="contact-seaction1-descrption">
                        <p>{{ isset($websetting->address) ? $websetting->address : '' }}</p>
                    </div>
                    <div class="contact-seaction1-title">
                        <h1>Address</h1>
                    </div>
                </div>
                <div class="contact-seaction1-card1">
                    <div class="contact-seaction1-icon">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 9l9 6l9 -6l-9 -6l-9 6" />
                                <path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" />
                                <path d="M3 19l6 -6" />
                                <path d="M15 13l6 6" />
                            </svg>
                        </span>
                    </div>
                    <div class="contact-seaction1-descrption">
                        <p>{{ isset($websetting->email1) ? $websetting->email1 : '' }}</p>
                        <p>{{ isset($websetting->email2) ? $websetting->email2 : '' }}</p>
                    </div>
                    <div class="contact-seaction1-title">
                        <h1>Email Us</h1>
                    </div>
                </div>
                <div class="contact-seaction1-card1">
                    <div class="contact-seaction1-icon">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                            </svg>
                        </span>
                    </div>
                    <div class="contact-seaction1-descrption">
                        <p>{{ isset($websetting->phone1) ? $websetting->phone1 : '' }}</p>
                        <p>{{ isset($websetting->phone2) ? $websetting->phone2 : '' }}</p>
                    </div>
                    <div class="contact-seaction1-title">
                        <h1>Call Now</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .contact-seaction2-start -->
    <div class="contact-form-section">
        <h1>{{ isset($contact->title) ? $contact->title : '' }}</h1>
        <p>
            {{ isset($contact->desc) ? $contact->desc : '' }}
        </p>

        {{-- Show validation errors --}}
        @if ($errors->any())
            <div class="contact-form-errors"
                style="background: #fff0f0; border: 1.5px solid #e53935; color: #b71c1c; border-radius: 8px; padding: 1rem 1.5rem; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.2em;">
                    @foreach ($errors->all() as $error)
                        <li style="margin-bottom: 0.25em;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="contact-form" method="POST" action="{{ route('contactus.store') }}" novalidate
            onsubmit="return validateContactForm(this);">
            @csrf
            <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required />
            <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required />
            <input type="tel" name="phone" placeholder="Your Phone" value="{{ old('phone') }}" pattern="^[0-9+\-\s()]*$" />
            <input type="text" name="address" placeholder="Your Address" value="{{ old('address') }}" />
            <textarea name="message" placeholder="Your Message" rows="5" style="resize:vertical;">{{ old('message') }}</textarea>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- .contact-seaction3-start -->
    <div class="hmbc-seaction1-main">
        <div class="map-container">
            <iframe src="{{ $websetting->address_link }}" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
@endsection
@section('script')
<script>
    // Simple client-side validation for better UX
    function validateContactForm(form) {
        let valid = true;
        let messages = [];

        // Name validation
        const name = form.name.value.trim();
        if (!name) {
            valid = false;
            messages.push("Name is required.");
        } else if (name.length > 255) {
            valid = false;
            messages.push("Name must not exceed 255 characters.");
        }

        // Email validation
        const email = form.email.value.trim();
        if (!email) {
            valid = false;
            messages.push("Email is required.");
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            valid = false;
            messages.push("Please enter a valid email address.");
        } else if (email.length > 255) {
            valid = false;
            messages.push("Email must not exceed 255 characters.");
        }

        // Phone validation (optional)
        const phone = form.phone.value.trim();
        if (phone && phone.length > 20) {
            valid = false;
            messages.push("Phone must not exceed 20 characters.");
        }

        // Address validation (optional)
        const address = form.address.value.trim();
        if (address && address.length > 500) {
            valid = false;
            messages.push("Address must not exceed 500 characters.");
        }

        // Show errors if any
        let errorDiv = document.querySelector('.contact-form-errors');
        if (!valid) {
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'contact-form-errors';
                errorDiv.style.background = '#fff0f0';
                errorDiv.style.border = '1.5px solid #e53935';
                errorDiv.style.color = '#b71c1c';
                errorDiv.style.borderRadius = '8px';
                errorDiv.style.padding = '1rem 1.5rem';
                errorDiv.style.marginBottom = '1rem';
                form.parentNode.insertBefore(errorDiv, form);
            }
            errorDiv.innerHTML = '<ul style="margin:0; padding-left:1.2em;">' +
                messages.map(msg => `<li style="margin-bottom:0.25em;">${msg}</li>`).join('') +
                '</ul>';
            return false;
        } else if (errorDiv) {
            errorDiv.remove();
        }
        return true;
    }
</script>
@endsection
