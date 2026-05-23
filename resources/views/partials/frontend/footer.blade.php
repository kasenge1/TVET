@php
    $contact = \App\Models\SiteSetting::getContactSettings();
    $social = \App\Models\SiteSetting::getSocialSettings();
@endphp

<footer class="fe-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand & Description -->
            <div class="col-lg-4 col-md-6">
                <div class="fe-footer-brand">
                    <i class="bi bi-mortarboard-fill" style="color: var(--fe-primary);"></i> TVET Revision
                </div>
                <p class="fe-footer-desc">
                    Your comprehensive platform for TVET exam preparation. Access thousands of past papers, study materials, and practice questions to ace your KNEC examinations.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if($social['facebook'])
                    <a href="{{ $social['facebook'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($social['twitter'])
                    <a href="{{ $social['twitter'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-twitter-x"></i></a>
                    @endif
                    @if($social['instagram'])
                    <a href="{{ $social['instagram'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($social['youtube'])
                    <a href="{{ $social['youtube'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if($social['tiktok'])
                    <a href="{{ $social['tiktok'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if($social['linkedin'])
                    <a href="{{ $social['linkedin'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #94a3b8;"><i class="bi bi-linkedin"></i></a>
                    @endif
                    @if($social['whatsapp'])
                    <a href="https://wa.me/{{ $social['whatsapp'] }}" target="_blank" class="fe-social-icon" style="border-color: rgba(255,255,255,0.15); color: #25d366;"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fe-footer-heading">Quick Links</h6>
                <a href="{{ route('home') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Home</a>
                <a href="{{ route('courses.index') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Courses</a>
                <a href="{{ route('about') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>About Us</a>
                <a href="{{ route('contact') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Contact</a>
                @guest
                <a href="{{ route('register') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Register</a>
                @endguest
            </div>

            <!-- Resources -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fe-footer-heading">Resources</h6>
                <a href="{{ route('faq') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>FAQ</a>
                <a href="{{ route('privacy') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Privacy Policy</a>
                <a href="{{ route('terms') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Terms of Service</a>
                <a href="{{ route('contact') }}" class="fe-footer-link"><i class="bi bi-chevron-right"></i>Support</a>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fe-footer-heading">Contact Us</h6>
                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-geo-alt-fill me-3 mt-1" style="color: var(--fe-primary);"></i>
                    <span style="color: #94a3b8; font-size: 0.9rem;">
                        {{ $contact['address'] }}
                        @if($contact['address_line2'])
                            <br>{{ $contact['address_line2'] }}
                        @endif
                    </span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-envelope-fill me-3" style="color: var(--fe-primary);"></i>
                    <a href="mailto:{{ $contact['email'] }}" class="fe-footer-link" style="display: inline;">{{ $contact['email'] }}</a>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-telephone-fill me-3" style="color: var(--fe-primary);"></i>
                    <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="fe-footer-link" style="display: inline;">{{ $contact['phone'] }}</a>
                </div>
                @if($contact['working_hours'])
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-fill me-3" style="color: var(--fe-primary);"></i>
                    <span style="color: #94a3b8; font-size: 0.9rem;">{{ $contact['working_hours'] }}</span>
                </div>
                @endif
            </div>
        </div>

        <hr class="fe-footer-divider">

        <!-- Bottom Bar -->
        <div class="fe-footer-bottom d-flex flex-wrap justify-content-between align-items-center">
            <p class="mb-0">&copy; {{ date('Y') }} TVET Revision. All rights reserved.</p>
            <div>
                <a href="{{ route('privacy') }}" class="me-3">Privacy Policy</a>
                <a href="{{ route('terms') }}">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
