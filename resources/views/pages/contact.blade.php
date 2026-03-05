<x-app-layout>
    <x-slot name="title">Contact Us - ShopWithCarl</x-slot>

    <div id="wrapper">
        <!-- Page Title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center">Contact Us</div>
                <p class="text-center text-secondary mt-2">We'd love to hear from you</p>
            </div>
        </div>

        <!-- Map -->
        <section class="flat-spacing-9">
            <div class="container">
                <div class="tf-grid-layout md-col-2 gap-30">
                    <!-- Contact Info -->
                    <div>
                        <h5 class="fw-bold mb-4">Get in Touch</h5>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Address</h6>
                                <p class="text-secondary">{{ setting('site_address', 'Kampala, Uganda') }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Phone</h6>
                                <p class="text-secondary">{{ setting('site_phone', '+256 700 000 000') }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Email</h6>
                                <p class="text-secondary">{{ setting('site_email', 'info@shopwithcarl.com') }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-5 mb-1">Working Hours</h6>
                                <p class="text-secondary">{{ setting('working_hours', 'Mon - Sat: 9:00 AM - 6:00 PM') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div>
                        <h5 class="fw-bold mb-4">Send Us a Message</h5>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('pages.contact.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="mb-2 fw-5">Your Name</label>
                                <div class="tf-field">
                                    <input type="text" class="tf-input" id="name" name="name"
                                           value="{{ old('name') }}" required placeholder="Full name">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="mb-2 fw-5">Email Address</label>
                                <div class="tf-field">
                                    <input type="email" class="tf-input" id="email" name="email"
                                           value="{{ old('email') }}" required placeholder="email@example.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="mb-2 fw-5">Message</label>
                                <div class="tf-field">
                                    <textarea class="tf-input" id="message" name="message" rows="5"
                                              required placeholder="How can we help you?">{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn">
                                    <span>Send Message</span>
                                    <i class="icon icon-arrow1-top-left"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
