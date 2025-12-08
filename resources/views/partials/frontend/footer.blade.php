@php
    $contact = \App\Models\SiteSetting::getContactSettings();
    $social = \App\Models\SiteSetting::getSocialSettings();
@endphp

<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand & Description -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-mortarboard-fill me-2 text-primary"></i>TVET Revision
                </h5>
                <p class="text-white-50 mb-4">
                    Your comprehensive platform for TVET exam preparation. Access thousands of past papers, study materials, and practice questions to ace your KNEC examinations.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if($social['facebook'])
                    <a href="{{ $social['facebook'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    @endif
                    @if($social['twitter'])
                    <a href="{{ $social['twitter'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    @endif
                    @if($social['instagram'])
                    <a href="{{ $social['instagram'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    @endif
                    @if($social['youtube'])
                    <a href="{{ $social['youtube'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-youtube"></i>
                    </a>
                    @endif
                    @if($social['tiktok'])
                    <a href="{{ $social['tiktok'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-tiktok"></i>
                    </a>
                    @endif
                    @if($social['linkedin'])
                    <a href="{{ $social['linkedin'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    @endif
                    @if($social['whatsapp'])
                    <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fw-bold mb-3 text-uppercase small">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('courses.index') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Courses
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('about') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Contact
                        </a>
                    </li>
                    @guest
                    <li class="mb-2">
                        <a href="{{ route('register') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Register
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>

            <!-- Resources -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fw-bold mb-3 text-uppercase small">Resources</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('faq') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('terms') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Terms of Service
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="text-white-50 text-decoration-none hover-white">
                            <i class="bi bi-chevron-right small me-1"></i>Support
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase small">Contact Us</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                        <span class="text-white-50">
                            {{ $contact['address'] }}
                            @if($contact['address_line2'])
                                <br>{{ $contact['address_line2'] }}
                            @endif
                        </span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-primary me-3"></i>
                        <a href="mailto:{{ $contact['email'] }}" class="text-white-50 text-decoration-none hover-white">
                            {{ $contact['email'] }}
                        </a>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-primary me-3"></i>
                        <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="text-white-50 text-decoration-none hover-white">
                            {{ $contact['phone'] }}
                        </a>
                    </li>
                    @if($contact['working_hours'])
                    <li class="d-flex align-items-center">
                        <i class="bi bi-clock-fill text-primary me-3"></i>
                        <span class="text-white-50">{{ $contact['working_hours'] }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <hr class="border-secondary opacity-25">

        <!-- Bottom Bar -->
        <div class="row align-items-center pt-2">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-white-50 small">
                    &copy; {{ date('Y') }} TVET Revision. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="{{ route('privacy') }}" class="text-white-50 small text-decoration-none me-3 hover-white">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-white-50 small text-decoration-none hover-white">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-white:hover {
        color: #fff !important;
        transition: color 0.2s ease;
    }
</style>
