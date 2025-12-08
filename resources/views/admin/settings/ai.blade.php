@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'AI Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <x-card title="AI Configuration" class="border-primary">
            <form action="{{ route('admin.settings.ai.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <i class="bi bi-robot me-2"></i>
                    Configure AI settings for generating question explanations and study assistance.
                </div>

                <div class="mb-4">
                    <label for="ai_provider" class="form-label fw-medium">AI Provider <span class="text-danger">*</span></label>
                    <select class="form-select @error('ai_provider') is-invalid @enderror"
                            id="ai_provider"
                            name="ai_provider">
                        <option value="openai" {{ ($aiSettings['provider'] ?? 'openai') === 'openai' ? 'selected' : '' }}>OpenAI (GPT)</option>
                        <option value="anthropic" {{ ($aiSettings['provider'] ?? '') === 'anthropic' ? 'selected' : '' }}>Anthropic (Claude)</option>
                        <option value="google" {{ ($aiSettings['provider'] ?? '') === 'google' ? 'selected' : '' }}>Google (Gemini)</option>
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
                           placeholder="{{ !empty($aiSettings['api_key']) ? '••••••••••••••••' : 'Enter API Key' }}">
                    @error('ai_api_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank to keep existing key</small>
                </div>

                <div class="mb-4">
                    <label for="ai_model" class="form-label fw-medium">Model <span class="text-danger">*</span></label>
                    <select class="form-select @error('ai_model') is-invalid @enderror"
                            id="ai_model"
                            name="ai_model">
                        <optgroup label="OpenAI Models" class="openai-models">
                            <option value="gpt-4o" {{ ($aiSettings['model'] ?? '') === 'gpt-4o' ? 'selected' : '' }}>GPT-4o (Latest)</option>
                            <option value="gpt-4-turbo" {{ ($aiSettings['model'] ?? '') === 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo</option>
                            <option value="gpt-4" {{ ($aiSettings['model'] ?? '') === 'gpt-4' ? 'selected' : '' }}>GPT-4</option>
                            <option value="gpt-3.5-turbo" {{ ($aiSettings['model'] ?? 'gpt-3.5-turbo') === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (Economical)</option>
                        </optgroup>
                        <optgroup label="Anthropic Models" class="anthropic-models">
                            <option value="claude-sonnet-4-20250514" {{ ($aiSettings['model'] ?? '') === 'claude-sonnet-4-20250514' ? 'selected' : '' }}>Claude Sonnet 4 (Latest)</option>
                            <option value="claude-3-5-sonnet-20241022" {{ ($aiSettings['model'] ?? '') === 'claude-3-5-sonnet-20241022' ? 'selected' : '' }}>Claude 3.5 Sonnet</option>
                            <option value="claude-3-opus-20240229" {{ ($aiSettings['model'] ?? '') === 'claude-3-opus-20240229' ? 'selected' : '' }}>Claude 3 Opus (Most Capable)</option>
                            <option value="claude-3-haiku-20240307" {{ ($aiSettings['model'] ?? '') === 'claude-3-haiku-20240307' ? 'selected' : '' }}>Claude 3 Haiku (Fast)</option>
                        </optgroup>
                        <optgroup label="Google Gemini Models" class="google-models">
                            <option value="gemini-2.0-flash" {{ ($aiSettings['model'] ?? '') === 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash (Latest)</option>
                            <option value="gemini-1.5-pro" {{ ($aiSettings['model'] ?? '') === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro</option>
                            <option value="gemini-1.5-flash" {{ ($aiSettings['model'] ?? '') === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Fast)</option>
                            <option value="gemini-1.0-pro" {{ ($aiSettings['model'] ?? '') === 'gemini-1.0-pro' ? 'selected' : '' }}>Gemini 1.0 Pro</option>
                        </optgroup>
                    </select>
                    @error('ai_model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="ai_max_tokens" class="form-label fw-medium">Max Tokens <span class="text-danger">*</span></label>
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
                        <small class="text-muted">Maximum response length (100-4000)</small>
                    </div>

                    <div class="col-md-6">
                        <label for="ai_temperature" class="form-label fw-medium">Temperature <span class="text-danger">*</span></label>
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
                        <small class="text-muted">Creativity level (0 = focused, 1 = creative)</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="ai_system_prompt" class="form-label fw-medium">System Prompt</label>
                    <textarea class="form-control @error('ai_system_prompt') is-invalid @enderror"
                              id="ai_system_prompt"
                              name="ai_system_prompt"
                              rows="4"
                              placeholder="Optional custom instructions for the AI...">{{ old('ai_system_prompt', $aiSettings['system_prompt'] ?? '') }}</textarea>
                    @error('ai_system_prompt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Custom instructions to guide AI responses (optional)</small>
                </div>

                <div class="border-top pt-4 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn-modern btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save AI Settings
                    </button>
                    @if(!empty($aiSettings['api_key']))
                    <button type="button" class="btn btn-outline-success" onclick="testAiConnection()">
                        <i class="bi bi-plug me-2"></i>Test Connection
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="disconnectAi()">
                        <i class="bi bi-x-circle me-2"></i>Disconnect
                    </button>
                    @endif
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>

            <!-- Hidden form for disconnecting AI -->
            <form id="disconnectAiForm" action="{{ route('admin.settings.ai.disconnect') }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="AI Status" class="border-secondary">
            <div class="text-center py-3">
                @if(!empty($aiSettings['api_key']))
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-robot fs-3"></i>
                    </div>
                    <h5 class="text-success">Configured</h5>
                    <p class="text-muted small">AI features are enabled</p>
                @else
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-robot fs-3"></i>
                    </div>
                    <h5 class="text-warning">Not Configured</h5>
                    <p class="text-muted small">Add an API key to enable AI</p>
                @endif
            </div>
        </x-card>

        <x-card title="AI Features" class="mt-4">
            <ul class="list-unstyled mb-0">
                <li class="d-flex align-items-start">
                    <i class="bi bi-lightbulb text-warning me-2 mt-1"></i>
                    <div>
                        <strong>Answer Generation</strong>
                        <div class="text-muted small">Auto-generate detailed answers and explanations for questions</div>
                    </div>
                </li>
            </ul>
        </x-card>

        <x-card title="Pricing Info" class="mt-4">
            <div class="small">
                <div class="mb-3 p-2 bg-light rounded">
                    <strong><i class="bi bi-openai me-1"></i>OpenAI</strong>
                    <div class="text-muted">
                        GPT-4o: ~$2.50/1M input tokens<br>
                        GPT-3.5: ~$0.50/1M tokens<br>
                        <a href="https://openai.com/pricing" target="_blank" class="text-primary">View pricing</a>
                    </div>
                </div>
                <div class="mb-3 p-2 bg-light rounded">
                    <strong><i class="bi bi-stars me-1"></i>Anthropic</strong>
                    <div class="text-muted">
                        Claude Sonnet 4: ~$3/1M input<br>
                        Claude 3 Haiku: ~$0.25/1M tokens<br>
                        <a href="https://anthropic.com/pricing" target="_blank" class="text-primary">View pricing</a>
                    </div>
                </div>
                <div class="p-2 bg-light rounded">
                    <strong><i class="bi bi-google me-1"></i>Google</strong>
                    <div class="text-muted">
                        Gemini 2.0 Flash: Free tier available<br>
                        Gemini 1.5 Pro: ~$1.25/1M tokens<br>
                        <a href="https://ai.google.dev/pricing" target="_blank" class="text-primary">View pricing</a>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
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
                timer: 5000,
                showConfirmButton: true
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
            text: 'Failed to test AI connection. Please try again.'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function disconnectAi() {
    Swal.fire({
        title: 'Disconnect AI?',
        html: `
            <div class="text-start">
                <p>This will remove the API key and disable AI features:</p>
                <ul class="small text-muted">
                    <li>AI-powered answer generation for questions</li>
                </ul>
                <p class="mb-0"><strong>You can reconnect anytime by entering a new API key.</strong></p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-x-circle me-1"></i> Yes, Disconnect',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('disconnectAiForm').submit();
        }
    });
}
</script>
@endpush
@endsection
