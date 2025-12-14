@extends('layouts.frontend')

@section('title', 'Contact Us - TVET Revision')
@section('description', 'Get in touch with TVET Revision. We are here to help you with any questions or concerns.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-5">
    <div class="container py-4 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-3">Contact Us</h1>
                <p class="lead opacity-90">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Send us a Message</h4>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <!-- reCAPTCHA -->
                                    <x-recaptcha form="contact" />
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Contact Information</h5>
                        <div class="d-flex mb-4">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:{{ $contact['email'] }}" class="text-decoration-none">{{ $contact['email'] }}</a>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="feature-icon bg-success bg-opacity-10 text-success me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Phone</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="text-decoration-none">{{ $contact['phone'] }}</a>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="feature-icon bg-warning bg-opacity-10 text-warning me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Location</h6>
                                <p class="text-muted mb-0">
                                    {{ $contact['address'] }}
                                    @if($contact['address_line2'])
                                        <br>{{ $contact['address_line2'] }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($contact['working_hours'])
                        <div class="d-flex">
                            <div class="feature-icon bg-info bg-opacity-10 text-info me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Working Hours</h6>
                                <p class="text-muted mb-0">{{ $contact['working_hours'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($social['facebook'] || $social['twitter'] || $social['instagram'] || $social['youtube'] || $social['tiktok'] || $social['linkedin'] || $social['whatsapp'])
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Follow Us</h5>
                        <div class="d-flex flex-wrap gap-3">
                            @if($social['facebook'])
                            <a href="{{ $social['facebook'] }}" target="_blank" class="btn btn-outline-primary btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-facebook"></i>
                            </a>
                            @endif
                            @if($social['twitter'])
                            <a href="{{ $social['twitter'] }}" target="_blank" class="btn btn-outline-dark btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            @endif
                            @if($social['instagram'])
                            <a href="{{ $social['instagram'] }}" target="_blank" class="btn btn-outline-danger btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-instagram"></i>
                            </a>
                            @endif
                            @if($social['youtube'])
                            <a href="{{ $social['youtube'] }}" target="_blank" class="btn btn-outline-danger btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-youtube"></i>
                            </a>
                            @endif
                            @if($social['tiktok'])
                            <a href="{{ $social['tiktok'] }}" target="_blank" class="btn btn-outline-dark btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            @endif
                            @if($social['linkedin'])
                            <a href="{{ $social['linkedin'] }}" target="_blank" class="btn btn-outline-primary btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            @endif
                            @if($social['whatsapp'])
                            <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="btn btn-outline-success btn-lg rounded-circle" style="width: 50px; height: 50px;">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
            <p class="text-muted">Quick answers to common questions</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I create an account?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Click on the "Get Started" button on our homepage, fill in your details, and verify your email address. It only takes a few seconds!
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Is TVET Revision free to use?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Yes! You can access all our questions and answers for free. We offer a premium subscription for an ad-free experience.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How can I subscribe to premium?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                After logging in, go to your dashboard and click on "Subscription". Choose your preferred plan and complete payment via M-Pesa.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can I access TVET Revision on my phone?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Absolutely! Our platform is fully mobile-responsive, so you can study anywhere, anytime from your phone or tablet.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('faq') }}" class="btn btn-outline-primary">
                        <i class="bi bi-question-circle me-2"></i>View All FAQs
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
