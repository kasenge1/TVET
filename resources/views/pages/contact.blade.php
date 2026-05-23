@extends('layouts.frontend')

@section('title', 'Contact Us - TVET Revision')
@section('description', 'Get in touch with TVET Revision. We are here to help you with any questions or concerns.')

@section('content')
<!-- Hero Section -->
<section class="fe-page-hero text-white">
    <div class="container text-center" style="padding: 3.5rem 0;">
        <h1 class="fe-hero-title" style="font-size: 2.5rem;">Contact Us</h1>
        <p class="fe-hero-subtitle mx-auto" style="max-width: 550px;">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="fe-section" style="background: var(--fe-bg);">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="fe-sidebar-card" style="padding: 2rem;">
                    <h4 class="fw-bold mb-4" style="font-size: 1.25rem;">Send us a Message</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--fe-radius-sm);">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--fe-radius-sm);">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold" style="font-size: 0.85rem;">Full Name</label>
                                <input type="text" class="fe-search-input w-100 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold" style="font-size: 0.85rem;">Email Address</label>
                                <input type="email" class="fe-search-input w-100 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label fw-semibold" style="font-size: 0.85rem;">Subject</label>
                                <input type="text" class="fe-search-input w-100 @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold" style="font-size: 0.85rem;">Message</label>
                                <textarea class="fe-search-input w-100 @error('message') is-invalid @enderror" id="message" name="message" rows="5" required style="resize: vertical;">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <x-recaptcha form="contact" />
                            </div>
                            <div class="col-12">
                                <button type="submit" class="fe-btn fe-btn-primary fe-btn-lg">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="fe-sidebar-card mb-4">
                    <h5 class="fe-sidebar-card-title">Contact Information</h5>

                    <div class="fe-info-item">
                        <div class="fe-info-icon" style="background: #eff6ff; color: #2563eb;"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="fe-info-label">Email</div>
                            <div class="fe-info-value">
                                <a href="mailto:{{ $contact['email'] }}" class="text-decoration-none" style="color: var(--fe-text);">{{ $contact['email'] }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="fe-info-item">
                        <div class="fe-info-icon" style="background: #ecfdf5; color: #059669;"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="fe-info-label">Phone</div>
                            <div class="fe-info-value">
                                <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="text-decoration-none" style="color: var(--fe-text);">{{ $contact['phone'] }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="fe-info-item">
                        <div class="fe-info-icon" style="background: #fffbeb; color: #d97706;"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="fe-info-label">Location</div>
                            <div class="fe-info-value">
                                {{ $contact['address'] }}
                                @if($contact['address_line2'])
                                    <br>{{ $contact['address_line2'] }}
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($contact['working_hours'])
                    <div class="fe-info-item mb-0">
                        <div class="fe-info-icon" style="background: #f0fdfa; color: #0d9488;"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="fe-info-label">Working Hours</div>
                            <div class="fe-info-value">{{ $contact['working_hours'] }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($social['facebook'] || $social['twitter'] || $social['instagram'] || $social['youtube'] || $social['tiktok'] || $social['linkedin'] || $social['whatsapp'])
                <div class="fe-sidebar-card">
                    <h5 class="fe-sidebar-card-title">Follow Us</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @if($social['facebook'])
                        <a href="{{ $social['facebook'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-facebook"></i></a>
                        @endif
                        @if($social['twitter'])
                        <a href="{{ $social['twitter'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-twitter-x"></i></a>
                        @endif
                        @if($social['instagram'])
                        <a href="{{ $social['instagram'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($social['youtube'])
                        <a href="{{ $social['youtube'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-youtube"></i></a>
                        @endif
                        @if($social['tiktok'])
                        <a href="{{ $social['tiktok'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-tiktok"></i></a>
                        @endif
                        @if($social['linkedin'])
                        <a href="{{ $social['linkedin'] }}" target="_blank" class="fe-social-icon"><i class="bi bi-linkedin"></i></a>
                        @endif
                        @if($social['whatsapp'])
                        <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="fe-social-icon" style="border-color: #25d366; color: #25d366;"><i class="bi bi-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="fe-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="fe-section-label">FAQ</span>
            <h2 class="fe-section-title">Frequently Asked Questions</h2>
            <p class="fe-section-subtitle mx-auto">Quick answers to common questions</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion fe-accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I create an account?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="color: var(--fe-text-secondary);">
                                Click on the "Get Started" button on our homepage, fill in your details, and verify your email address. It only takes a few seconds!
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Is TVET Revision free to use?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="color: var(--fe-text-secondary);">
                                Yes! You can access all our questions and answers for free. We offer a premium subscription for an ad-free experience.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How can I subscribe to premium?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="color: var(--fe-text-secondary);">
                                After logging in, go to your dashboard and click on "Subscription". Choose your preferred plan and complete payment via M-Pesa.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can I access TVET Revision on my phone?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="color: var(--fe-text-secondary);">
                                Absolutely! Our platform is fully mobile-responsive, so you can study anywhere, anytime from your phone or tablet.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('faq') }}" class="fe-btn fe-btn-primary" style="background: transparent; color: var(--fe-primary); border: 1.5px solid var(--fe-primary);">
                        <i class="bi bi-question-circle me-2"></i>View All FAQs
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
