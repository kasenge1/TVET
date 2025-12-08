@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Create New Role')

@section('main')
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <x-card>
                <h5 class="mb-4"><i class="bi bi-shield-plus me-2"></i>Role Details</h5>

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Role Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g., Content Reviewer"
                           required>
                    <small class="text-muted">The name will be converted to a slug (e.g., "Content Reviewer" becomes "content-reviewer")</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3"><i class="bi bi-key me-2"></i>Permissions</h5>
                <p class="text-muted small mb-4">Select the permissions this role should have. Users with this role will only be able to perform actions for which they have permission.</p>

                @foreach($permissions as $resource => $resourcePermissions)
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="mb-0 text-capitalize">
                            <i class="bi bi-folder me-2"></i>{{ $resource }}
                        </h6>
                        <button type="button" class="btn btn-link btn-sm ms-2 select-all-btn" data-resource="{{ $resource }}">
                            Select All
                        </button>
                    </div>
                    <div class="row">
                        @foreach($resourcePermissions as $permission)
                        @php
                            $action = explode(' ', $permission->name)[0];
                            $actionColors = [
                                'view' => 'info',
                                'create' => 'success',
                                'edit' => 'warning',
                                'delete' => 'danger',
                                'publish' => 'primary',
                                'manage' => 'dark',
                                'send' => 'secondary',
                                'impersonate' => 'danger',
                                'import' => 'success',
                                'export' => 'info',
                                'block' => 'danger',
                                'clear' => 'warning',
                            ];
                            $actionIcons = [
                                'view' => 'bi-eye',
                                'create' => 'bi-plus-circle',
                                'edit' => 'bi-pencil',
                                'delete' => 'bi-trash',
                                'publish' => 'bi-globe',
                                'manage' => 'bi-gear',
                                'send' => 'bi-send',
                                'impersonate' => 'bi-person-badge',
                                'import' => 'bi-upload',
                                'export' => 'bi-download',
                                'block' => 'bi-slash-circle',
                                'clear' => 'bi-eraser',
                            ];
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="perm_{{ $permission->id }}"
                                       data-resource="{{ $resource }}"
                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    <span class="badge bg-{{ $actionColors[$action] ?? 'secondary' }} me-1" style="font-size: 0.65rem;">
                                        <i class="bi {{ $actionIcons[$action] ?? 'bi-check' }}"></i>
                                    </span>
                                    {{ ucfirst($action) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </x-card>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <x-card class="sticky-top" style="top: 20px;">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Tips</h5>

                <div class="alert alert-info small mb-3">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tip:</strong> Start with minimal permissions and add more as needed. It's easier to grant than revoke access.
                </div>

                <div class="mb-3">
                    <h6>Common Role Patterns:</h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>Question Editor:</strong> View courses/units/levels + Create/Edit questions</li>
                        <li><strong>Content Reviewer:</strong> View all content (no create/edit/delete)</li>
                        <li><strong>Course Manager:</strong> Full access to courses and units only</li>
                    </ul>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Select all permissions for a resource
document.querySelectorAll('.select-all-btn').forEach(button => {
    button.addEventListener('click', function() {
        const resource = this.dataset.resource;
        const checkboxes = document.querySelectorAll(`.permission-checkbox[data-resource="${resource}"]`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);

        checkboxes.forEach(cb => cb.checked = !allChecked);
        this.textContent = allChecked ? 'Select All' : 'Deselect All';
    });
});
</script>
@endpush
