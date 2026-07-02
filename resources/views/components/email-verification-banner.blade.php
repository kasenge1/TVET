@auth
    @if(!auth()->user()->hasVerifiedEmail() && !session('verification_banner_dismissed'))
    <div id="emailVerificationBanner"
         class="alert alert-warning alert-dismissible mb-0 rounded-0 border-0 border-bottom border-warning py-2"
         role="alert"
         style="border-left: 4px solid #ffc107 !important;">
        <div class="container d-flex flex-wrap align-items-center gap-2">
            <i class="bi bi-envelope-exclamation-fill text-warning fs-5 flex-shrink-0"></i>
            <span class="flex-grow-1 small">
                <strong>Please verify your email address.</strong>
                Check your inbox at <strong>{{ auth()->user()->email }}</strong> for a verification link.
            </span>

            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                @if(session('verification_resent'))
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>Email sent!
                    </span>
                @else
                    <form method="POST" action="{{ route('learn.resend-verification') }}" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-warning btn-sm py-1 px-2"
                                style="font-size: 0.78rem;">
                            <i class="bi bi-send me-1"></i>Resend Email
                        </button>
                    </form>
                @endif

                {{-- Dismiss for this session --}}
                <form method="POST" action="{{ route('learn.dismiss-verification-banner') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn-close btn-close-sm"
                            aria-label="Dismiss"
                            title="Dismiss for this session"
                            style="font-size: 0.65rem;">
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth
