@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'System Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="General Settings">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="app_name" class="form-label fw-medium">Application Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('app_name') is-invalid @enderror"
                           id="app_name"
                           name="app_name"
                           value="{{ old('app_name', config('app.name')) }}"
                           required>
                    @error('app_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This will be displayed throughout the application</small>
                </div>

                <div class="mb-4">
                    <label for="app_url" class="form-label fw-medium">Application URL</label>
                    <input type="url"
                           class="form-control @error('app_url') is-invalid @enderror"
                           id="app_url"
                           name="app_url"
                           value="{{ old('app_url', config('app.url')) }}"
                           readonly>
                    @error('app_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Read-only: Change in .env file</small>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="timezone" class="form-label fw-medium">Timezone</label>
                        <select class="form-select @error('timezone') is-invalid @enderror"
                                id="timezone"
                                name="timezone">
                            <option value="Africa/Nairobi" {{ config('app.timezone') === 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                            <option value="UTC" {{ config('app.timezone') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Africa/Lagos" {{ config('app.timezone') === 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos (WAT)</option>
                            <option value="Africa/Johannesburg" {{ config('app.timezone') === 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg (SAST)</option>
                        </select>
                        @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="locale" class="form-label fw-medium">Language</label>
                        <select class="form-select @error('locale') is-invalid @enderror"
                                id="locale"
                                name="locale">
                            <option value="en" {{ config('app.locale') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ config('app.locale') === 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                        @error('locale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4 mb-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save General Settings
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Contact Information" class="mt-4 border-info" id="contact">
            <form action="{{ route('admin.settings.contact') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    This information will be displayed on the Contact page and footer.
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('contact_email') is-invalid @enderror"
                               id="contact_email"
                               name="contact_email"
                               value="{{ old('contact_email', $contactSettings['email'] ?? '') }}"
                               required>
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label fw-medium">Phone Number <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('contact_phone') is-invalid @enderror"
                               id="contact_phone"
                               name="contact_phone"
                               value="{{ old('contact_phone', $contactSettings['phone'] ?? '') }}"
                               placeholder="+254 700 000 000"
                               required>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="contact_address" class="form-label fw-medium">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('contact_address') is-invalid @enderror"
                               id="contact_address"
                               name="contact_address"
                               value="{{ old('contact_address', $contactSettings['address'] ?? '') }}"
                               placeholder="Nairobi, Kenya"
                               required>
                        @error('contact_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contact_address_line2" class="form-label fw-medium">Address Line 2</label>
                        <input type="text"
                               class="form-control @error('contact_address_line2') is-invalid @enderror"
                               id="contact_address_line2"
                               name="contact_address_line2"
                               value="{{ old('contact_address_line2', $contactSettings['address_line2'] ?? '') }}"
                               placeholder="Tom Mboya Street">
                        @error('contact_address_line2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="contact_working_hours" class="form-label fw-medium">Working Hours</label>
                    <input type="text"
                           class="form-control @error('contact_working_hours') is-invalid @enderror"
                           id="contact_working_hours"
                           name="contact_working_hours"
                           value="{{ old('contact_working_hours', $contactSettings['working_hours'] ?? '') }}"
                           placeholder="Mon - Fri: 8:00 AM - 6:00 PM">
                    @error('contact_working_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-info px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Contact Information
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Social Media Links" class="mt-4 border-secondary" id="social">
            <form action="{{ route('admin.settings.social') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-share me-2"></i>
                    Add your social media links. These will be displayed in the footer and contact page.
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_facebook" class="form-label fw-medium">
                            <i class="bi bi-facebook text-primary me-1"></i>Facebook
                        </label>
                        <input type="url"
                               class="form-control @error('social_facebook') is-invalid @enderror"
                               id="social_facebook"
                               name="social_facebook"
                               value="{{ old('social_facebook', $socialSettings['facebook'] ?? '') }}"
                               placeholder="https://facebook.com/yourpage">
                        @error('social_facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_twitter" class="form-label fw-medium">
                            <i class="bi bi-twitter-x me-1"></i>Twitter/X
                        </label>
                        <input type="url"
                               class="form-control @error('social_twitter') is-invalid @enderror"
                               id="social_twitter"
                               name="social_twitter"
                               value="{{ old('social_twitter', $socialSettings['twitter'] ?? '') }}"
                               placeholder="https://twitter.com/yourhandle">
                        @error('social_twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_instagram" class="form-label fw-medium">
                            <i class="bi bi-instagram text-danger me-1"></i>Instagram
                        </label>
                        <input type="url"
                               class="form-control @error('social_instagram') is-invalid @enderror"
                               id="social_instagram"
                               name="social_instagram"
                               value="{{ old('social_instagram', $socialSettings['instagram'] ?? '') }}"
                               placeholder="https://instagram.com/yourprofile">
                        @error('social_instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_youtube" class="form-label fw-medium">
                            <i class="bi bi-youtube text-danger me-1"></i>YouTube
                        </label>
                        <input type="url"
                               class="form-control @error('social_youtube') is-invalid @enderror"
                               id="social_youtube"
                               name="social_youtube"
                               value="{{ old('social_youtube', $socialSettings['youtube'] ?? '') }}"
                               placeholder="https://youtube.com/@yourchannel">
                        @error('social_youtube')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="social_tiktok" class="form-label fw-medium">
                            <i class="bi bi-tiktok me-1"></i>TikTok
                        </label>
                        <input type="url"
                               class="form-control @error('social_tiktok') is-invalid @enderror"
                               id="social_tiktok"
                               name="social_tiktok"
                               value="{{ old('social_tiktok', $socialSettings['tiktok'] ?? '') }}"
                               placeholder="https://tiktok.com/@yourprofile">
                        @error('social_tiktok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="social_linkedin" class="form-label fw-medium">
                            <i class="bi bi-linkedin text-primary me-1"></i>LinkedIn
                        </label>
                        <input type="url"
                               class="form-control @error('social_linkedin') is-invalid @enderror"
                               id="social_linkedin"
                               name="social_linkedin"
                               value="{{ old('social_linkedin', $socialSettings['linkedin'] ?? '') }}"
                               placeholder="https://linkedin.com/company/yourcompany">
                        @error('social_linkedin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="social_whatsapp" class="form-label fw-medium">
                        <i class="bi bi-whatsapp text-success me-1"></i>WhatsApp Number
                    </label>
                    <input type="text"
                           class="form-control @error('social_whatsapp') is-invalid @enderror"
                           id="social_whatsapp"
                           name="social_whatsapp"
                           value="{{ old('social_whatsapp', $socialSettings['whatsapp'] ?? '') }}"
                           placeholder="254700000000">
                    @error('social_whatsapp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Enter number without + or spaces (e.g., 254700000000)</small>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-secondary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Social Media Links
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="M-Pesa Payment Settings" class="mt-4 border-success" id="payments">
            <form action="{{ route('admin.settings.mpesa') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Configure your M-Pesa API credentials for processing payments.
                    Get credentials from <a href="https://developer.safaricom.co.ke" target="_blank">Safaricom Developer Portal</a>
                </div>

                @if(!empty($mpesaSettings['consumer_key']))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    M-Pesa is configured. Callback URL: <code>{{ url('/api/mpesa/callback') }}</code>
                </div>
                @endif

                <div class="mb-4">
                    <label for="mpesa_consumer_key" class="form-label fw-medium">Consumer Key</label>
                    <input type="text"
                           class="form-control @error('mpesa_consumer_key') is-invalid @enderror"
                           id="mpesa_consumer_key"
                           name="mpesa_consumer_key"
                           value="{{ old('mpesa_consumer_key', $mpesaSettings['consumer_key'] ?? '') }}"
                           placeholder="Enter M-Pesa Consumer Key">
                    @error('mpesa_consumer_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mpesa_consumer_secret" class="form-label fw-medium">Consumer Secret</label>
                    <input type="password"
                           class="form-control @error('mpesa_consumer_secret') is-invalid @enderror"
                           id="mpesa_consumer_secret"
                           name="mpesa_consumer_secret"
                           value="{{ old('mpesa_consumer_secret', $mpesaSettings['consumer_secret'] ?? '') }}"
                           placeholder="Enter M-Pesa Consumer Secret">
                    @error('mpesa_consumer_secret')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing value</small>
                </div>

                <div class="mb-4">
                    <label for="mpesa_shortcode" class="form-label fw-medium">Business Short Code</label>
                    <input type="text"
                           class="form-control @error('mpesa_shortcode') is-invalid @enderror"
                           id="mpesa_shortcode"
                           name="mpesa_shortcode"
                           value="{{ old('mpesa_shortcode', $mpesaSettings['shortcode'] ?? '') }}"
                           placeholder="e.g., 174379 (sandbox) or your Paybill/Till">
                    @error('mpesa_shortcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mpesa_passkey" class="form-label fw-medium">Lipa Na M-Pesa Passkey</label>
                    <input type="password"
                           class="form-control @error('mpesa_passkey') is-invalid @enderror"
                           id="mpesa_passkey"
                           name="mpesa_passkey"
                           value="{{ old('mpesa_passkey', $mpesaSettings['passkey'] ?? '') }}"
                           placeholder="Enter Lipa Na M-Pesa Passkey">
                    @error('mpesa_passkey')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing value</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Environment</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mpesa_environment" id="sandbox" value="sandbox"
                               {{ ($mpesaSettings['environment'] ?? 'sandbox') === 'sandbox' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sandbox">
                            Sandbox (Testing)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mpesa_environment" id="production" value="production"
                               {{ ($mpesaSettings['environment'] ?? '') === 'production' ? 'checked' : '' }}>
                        <label class="form-check-label" for="production">
                            Production (Live)
                        </label>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-success px-4">
                        <i class="bi bi-check-circle me-2"></i>Save M-Pesa Settings
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Email Settings" class="mt-4 border-primary" id="email">
            <form action="{{ route('admin.settings.email') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-envelope me-2"></i>
                    Configure SMTP settings for sending emails (subscription notifications, password resets, etc.)
                </div>

                @if(\App\Models\SiteSetting::emailConfigured())
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    Email is configured. Click "Send Test Email" to verify settings.
                </div>
                @endif

                <div class="mb-4">
                    <label for="mail_driver" class="form-label fw-medium">Mail Driver</label>
                    <select class="form-select @error('mail_driver') is-invalid @enderror"
                            id="mail_driver"
                            name="mail_driver">
                        <option value="smtp" {{ ($emailSettings['driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendmail" {{ ($emailSettings['driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        <option value="mailgun" {{ ($emailSettings['driver'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        <option value="ses" {{ ($emailSettings['driver'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        <option value="log" {{ ($emailSettings['driver'] ?? '') === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                    </select>
                    @error('mail_driver')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="mail_host" class="form-label fw-medium">SMTP Host <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('mail_host') is-invalid @enderror"
                               id="mail_host"
                               name="mail_host"
                               value="{{ old('mail_host', $emailSettings['host'] ?? '') }}"
                               placeholder="smtp.gmail.com, smtp.mailtrap.io, etc.">
                        @error('mail_host')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="mail_port" class="form-label fw-medium">Port <span class="text-danger">*</span></label>
                        <select class="form-select @error('mail_port') is-invalid @enderror"
                                id="mail_port"
                                name="mail_port">
                            <option value="587" {{ ($emailSettings['port'] ?? '587') == '587' ? 'selected' : '' }}>587 (TLS - Recommended)</option>
                            <option value="465" {{ ($emailSettings['port'] ?? '') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                            <option value="25" {{ ($emailSettings['port'] ?? '') == '25' ? 'selected' : '' }}>25 (Unencrypted)</option>
                            <option value="2525" {{ ($emailSettings['port'] ?? '') == '2525' ? 'selected' : '' }}>2525 (Alternative)</option>
                        </select>
                        @error('mail_port')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="mail_username" class="form-label fw-medium">Username</label>
                        <input type="text"
                               class="form-control @error('mail_username') is-invalid @enderror"
                               id="mail_username"
                               name="mail_username"
                               value="{{ old('mail_username', $emailSettings['username'] ?? '') }}"
                               placeholder="your-email@gmail.com">
                        @error('mail_username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Usually your email address</small>
                    </div>

                    <div class="col-md-6">
                        <label for="mail_password" class="form-label fw-medium">Password / App Password</label>
                        <input type="password"
                               class="form-control @error('mail_password') is-invalid @enderror"
                               id="mail_password"
                               name="mail_password"
                               placeholder="{{ !empty($emailSettings['password']) ? '••••••••' : 'Enter password' }}">
                        @error('mail_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave blank to keep existing. For Gmail, use App Password.</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="mail_encryption" class="form-label fw-medium">Encryption</label>
                    <select class="form-select @error('mail_encryption') is-invalid @enderror"
                            id="mail_encryption"
                            name="mail_encryption">
                        <option value="tls" {{ ($emailSettings['encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                        <option value="ssl" {{ ($emailSettings['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ empty($emailSettings['encryption']) ? 'selected' : '' }}>None</option>
                    </select>
                    @error('mail_encryption')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Sender Information</h6>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="mail_from_address" class="form-label fw-medium">From Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('mail_from_address') is-invalid @enderror"
                               id="mail_from_address"
                               name="mail_from_address"
                               value="{{ old('mail_from_address', $emailSettings['from_address'] ?? '') }}"
                               placeholder="noreply@tvetrevision.co.ke">
                        @error('mail_from_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mail_from_name" class="form-label fw-medium">From Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('mail_from_name') is-invalid @enderror"
                               id="mail_from_name"
                               name="mail_from_name"
                               value="{{ old('mail_from_name', $emailSettings['from_name'] ?? 'TVET Revision') }}"
                               placeholder="TVET Revision">
                        @error('mail_from_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Email Settings
                    </button>
                    @if(\App\Models\SiteSetting::emailConfigured())
                    <button type="button" class="btn btn-outline-success ms-2" onclick="sendTestEmail()">
                        <i class="bi bi-send me-2"></i>Send Test Email
                    </button>
                    @endif
                </div>
            </form>
        </x-card>

        <x-card title="AI Answer Generation Settings" class="mt-4 border-primary" id="ai">
            <form action="{{ route('admin.settings.ai') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-robot me-2"></i>
                    Configure AI settings for automatic answer generation.
                    Supports OpenAI (GPT) and Anthropic (Claude) APIs.
                </div>

                @if(!empty($aiSettings['api_key']))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    AI is configured and ready to generate answers.
                </div>
                @endif

                <div class="mb-4">
                    <label for="ai_provider" class="form-label fw-medium">AI Provider</label>
                    <select class="form-select @error('ai_provider') is-invalid @enderror"
                            id="ai_provider"
                            name="ai_provider"
                            onchange="updateModelOptions()">
                        <option value="openai" {{ ($aiSettings['provider'] ?? 'openai') === 'openai' ? 'selected' : '' }}>OpenAI (GPT)</option>
                        <option value="anthropic" {{ ($aiSettings['provider'] ?? '') === 'anthropic' ? 'selected' : '' }}>Anthropic (Claude)</option>
                    </select>
                    @error('ai_provider')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ai_api_key" class="form-label fw-medium">API Key</label>
                    <input type="password"
                           class="form-control @error('ai_api_key') is-invalid @enderror"
                           id="ai_api_key"
                           name="ai_api_key"
                           value="{{ old('ai_api_key', $aiSettings['api_key'] ?? '') }}"
                           placeholder="Enter your API key">
                    @error('ai_api_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing key</small>
                </div>

                <div class="mb-4">
                    <label for="ai_model" class="form-label fw-medium">Model</label>
                    <select class="form-select @error('ai_model') is-invalid @enderror"
                            id="ai_model"
                            name="ai_model">
                        <!-- OpenAI Models -->
                        <optgroup label="OpenAI Models" id="openai-models">
                            <option value="gpt-4o-mini" {{ ($aiSettings['model'] ?? 'gpt-4o-mini') === 'gpt-4o-mini' ? 'selected' : '' }}>GPT-4o Mini (Fast & Affordable)</option>
                            <option value="gpt-4o" {{ ($aiSettings['model'] ?? '') === 'gpt-4o' ? 'selected' : '' }}>GPT-4o (Most Capable)</option>
                            <option value="gpt-4-turbo" {{ ($aiSettings['model'] ?? '') === 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo</option>
                            <option value="gpt-3.5-turbo" {{ ($aiSettings['model'] ?? '') === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (Budget)</option>
                        </optgroup>
                        <!-- Anthropic Models -->
                        <optgroup label="Anthropic Models" id="anthropic-models">
                            <option value="claude-3-5-sonnet-20241022" {{ ($aiSettings['model'] ?? '') === 'claude-3-5-sonnet-20241022' ? 'selected' : '' }}>Claude 3.5 Sonnet (Recommended)</option>
                            <option value="claude-3-5-haiku-20241022" {{ ($aiSettings['model'] ?? '') === 'claude-3-5-haiku-20241022' ? 'selected' : '' }}>Claude 3.5 Haiku (Fast)</option>
                            <option value="claude-3-opus-20240229" {{ ($aiSettings['model'] ?? '') === 'claude-3-opus-20240229' ? 'selected' : '' }}>Claude 3 Opus (Most Capable)</option>
                        </optgroup>
                    </select>
                    @error('ai_model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="ai_max_tokens" class="form-label fw-medium">Max Tokens</label>
                        <input type="number"
                               class="form-control @error('ai_max_tokens') is-invalid @enderror"
                               id="ai_max_tokens"
                               name="ai_max_tokens"
                               value="{{ old('ai_max_tokens', $aiSettings['max_tokens'] ?? 1000) }}"
                               min="100"
                               max="4000">
                        @error('ai_max_tokens')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum length of generated answer</small>
                    </div>

                    <div class="col-md-6">
                        <label for="ai_temperature" class="form-label fw-medium">Temperature</label>
                        <input type="number"
                               class="form-control @error('ai_temperature') is-invalid @enderror"
                               id="ai_temperature"
                               name="ai_temperature"
                               value="{{ old('ai_temperature', $aiSettings['temperature'] ?? 0.7) }}"
                               min="0"
                               max="1"
                               step="0.1">
                        @error('ai_temperature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">0 = precise, 1 = creative</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="ai_system_prompt" class="form-label fw-medium">System Prompt</label>
                    <textarea class="form-control @error('ai_system_prompt') is-invalid @enderror"
                              id="ai_system_prompt"
                              name="ai_system_prompt"
                              rows="4"
                              placeholder="Instructions for the AI when generating answers">{{ old('ai_system_prompt', $aiSettings['system_prompt'] ?? '') }}</textarea>
                    @error('ai_system_prompt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Customize how the AI responds to questions</small>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save AI Settings
                    </button>
                    @if(!empty($aiSettings['api_key']))
                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="testAiConnection()">
                        <i class="bi bi-lightning me-2"></i>Test Connection
                    </button>
                    @endif
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="System Information" class="border-info">
            <div class="mb-3">
                <div class="text-muted small mb-1">Laravel Version</div>
                <div class="fw-medium">{{ app()->version() }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">PHP Version</div>
                <div class="fw-medium">{{ PHP_VERSION }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Environment</div>
                <div>
                    @if(config('app.env') === 'production')
                        <span class="badge bg-success">Production</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ ucfirst(config('app.env')) }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Debug Mode</div>
                <div>
                    @if(config('app.debug'))
                        <span class="badge bg-danger">Enabled</span>
                    @else
                        <span class="badge bg-success">Disabled</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Database</div>
                <div class="fw-medium">{{ config('database.default') }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Cache Driver</div>
                <div class="fw-medium">{{ config('cache.default') }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-1">Queue Driver</div>
                <div class="fw-medium">{{ config('queue.default') }}</div>
            </div>
        </x-card>

        <x-card title="Maintenance Mode Control" class="mt-4 border-warning" id="maintenance">
            <p class="text-muted mb-3">
                Put your application into maintenance mode to perform updates or maintenance.
            </p>

            @if(app()->isDownForMaintenance())
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Maintenance Mode Active</strong><br>
                    <small>Frontend users see the maintenance page. Admin panel remains accessible.</small>
                </div>
                <form action="{{ route('admin.settings.maintenance.up') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-toggle-on me-2"></i>Disable Maintenance Mode
                    </button>
                </form>
            @else
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    When enabled, frontend/student users will see a maintenance page. <strong>Admin panel remains fully accessible.</strong>
                </div>
                <form action="{{ route('admin.settings.maintenance.down') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-toggle-off me-2"></i>Enable Maintenance Mode
                    </button>
                </form>
            @endif
        </x-card>

        <x-card title="Cache Management" class="mt-4">
            <div class="d-grid gap-2">
                <form action="{{ route('admin.settings.cache.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-2"></i>Clear Application Cache
                    </button>
                </form>

                <form action="{{ route('admin.settings.config.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Config Cache
                    </button>
                </form>

                <form action="{{ route('admin.settings.route.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-info w-100">
                        <i class="bi bi-signpost me-2"></i>Clear Route Cache
                    </button>
                </form>
            </div>
        </x-card>
    </div>
</div>

<!-- Maintenance Page Customization - Full Width -->
<div class="row mt-4">
    <div class="col-12">
        <x-card title="Maintenance Page Settings" class="border-primary">
            <form action="{{ route('admin.settings.maintenance.page') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Customize the maintenance page that users see when maintenance mode is enabled
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="title" class="form-label fw-medium">Page Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $maintenanceSettings->title ?? 'We\'ll Be Right Back!') }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="subtitle" class="form-label fw-medium">Subtitle <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('subtitle') is-invalid @enderror"
                               id="subtitle"
                               name="subtitle"
                               value="{{ old('subtitle', $maintenanceSettings->subtitle ?? 'System Under Maintenance') }}"
                               required>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label fw-medium">Main Message <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror"
                              id="message"
                              name="message"
                              rows="4"
                              required>{{ old('message', $maintenanceSettings->message ?? 'We\'re currently performing scheduled maintenance to enhance your learning experience. Our team is working diligently to bring the platform back online as soon as possible.') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="expected_duration" class="form-label fw-medium">Expected Duration <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('expected_duration') is-invalid @enderror"
                               id="expected_duration"
                               name="expected_duration"
                               value="{{ old('expected_duration', $maintenanceSettings->expected_duration ?? '1-2 Hours') }}"
                               placeholder="e.g., 1-2 Hours, 30 Minutes"
                               required>
                        @error('expected_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="support_email" class="form-label fw-medium">Support Email</label>
                        <input type="email"
                               class="form-control @error('support_email') is-invalid @enderror"
                               id="support_email"
                               name="support_email"
                               value="{{ old('support_email', $maintenanceSettings->support_email ?? '') }}"
                               placeholder="support@example.com">
                        @error('support_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty to use default from app URL</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="facebook_url" class="form-label fw-medium">Facebook URL</label>
                        <input type="url"
                               class="form-control @error('facebook_url') is-invalid @enderror"
                               id="facebook_url"
                               name="facebook_url"
                               value="{{ old('facebook_url', $maintenanceSettings->facebook_url ?? '') }}"
                               placeholder="https://facebook.com/yourpage">
                        @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="twitter_url" class="form-label fw-medium">Twitter/X URL</label>
                        <input type="url"
                               class="form-control @error('twitter_url') is-invalid @enderror"
                               id="twitter_url"
                               name="twitter_url"
                               value="{{ old('twitter_url', $maintenanceSettings->twitter_url ?? '') }}"
                               placeholder="https://twitter.com/yourhandle">
                        @error('twitter_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="instagram_url" class="form-label fw-medium">Instagram URL</label>
                        <input type="url"
                               class="form-control @error('instagram_url') is-invalid @enderror"
                               id="instagram_url"
                               name="instagram_url"
                               value="{{ old('instagram_url', $maintenanceSettings->instagram_url ?? '') }}"
                               placeholder="https://instagram.com/yourprofile">
                        @error('instagram_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="linkedin_url" class="form-label fw-medium">LinkedIn URL</label>
                        <input type="url"
                               class="form-control @error('linkedin_url') is-invalid @enderror"
                               id="linkedin_url"
                               name="linkedin_url"
                               value="{{ old('linkedin_url', $maintenanceSettings->linkedin_url ?? '') }}"
                               placeholder="https://linkedin.com/company/yourcompany">
                        @error('linkedin_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-4">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Maintenance Page Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
    // Update AI model options based on provider selection
    function updateModelOptions() {
        const provider = document.getElementById('ai_provider').value;
        const openaiModels = document.getElementById('openai-models');
        const anthropicModels = document.getElementById('anthropic-models');

        if (provider === 'openai') {
            openaiModels.style.display = '';
            anthropicModels.style.display = 'none';
            // Select first OpenAI model if current selection is Anthropic
            const currentModel = document.getElementById('ai_model').value;
            if (currentModel.startsWith('claude')) {
                document.getElementById('ai_model').value = 'gpt-4o-mini';
            }
        } else {
            openaiModels.style.display = 'none';
            anthropicModels.style.display = '';
            // Select first Anthropic model if current selection is OpenAI
            const currentModel = document.getElementById('ai_model').value;
            if (currentModel.startsWith('gpt')) {
                document.getElementById('ai_model').value = 'claude-3-5-sonnet-20241022';
            }
        }
    }

    // Test AI connection
    function testAiConnection() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Testing...';

        fetch('{{ route("admin.settings.ai.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Connection Successful',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Failed',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to test connection. Please try again.'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Send test email
    function sendTestEmail() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';

        fetch('{{ route("admin.settings.email.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Test Email Sent',
                    text: data.message,
                    timer: 5000,
                    showConfirmButton: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Failed',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to send test email. Please try again.'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateModelOptions();

        // Handle hash navigation (smooth scroll to section)
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target) {
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Add highlight effect
                    target.classList.add('border-3');
                    setTimeout(() => target.classList.remove('border-3'), 2000);
                }, 100);
            }
        }
    });
</script>
@endpush
@endsection
