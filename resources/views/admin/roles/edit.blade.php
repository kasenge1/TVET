@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Edit Role: ' . ucwords(str_replace('-', ' ', $role->name)))

@section('main')
<form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <x-card>
                <h5 class="mb-4"><i class="bi bi-shield-check me-2"></i>Role Details</h5>

                @if($role->name === 'super-admin')
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Protected Role:</strong> The Super Admin role cannot be renamed and always has all permissions.
                </div>
                @endif

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Role Name <span class="text-danger">*</span></label>
                    @if($role->name === 'super-admin')
                        <input type="text"
                               class="form-control"
                               value="{{ ucwords(str_replace('-', ' ', $role->name)) }}"
                               disabled>
                        <small class="text-muted">System role name cannot be changed</small>
                    @else
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', ucwords(str_replace('-', ' ', $role->name))) }}"
                               placeholder="e.g., Content Reviewer"
                               required>
                        <small class="text-muted">The name will be converted to a slug</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <hr class="my-4">

                <h5 class="mb-3"><i class="bi bi-key me-2"></i>Permissions</h5>

                @if($role->name === 'super-admin')
                <div class="alert alert-info small mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Super Admin automatically has all permissions. This cannot be changed.
                </div>
                @else
                <p class="text-muted small mb-4">Select the permissions this role should have. Users with this role will only be able to perform actions for which they have permission.</p>
                @endif

                @foreach($permissions as $resource => $resourcePermissions)
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="mb-0 text-capitalize">
                            <i class="bi bi-folder me-2"></i>{{ $resource }}
                        </h6>
                        @if($role->name !== 'super-admin')
                        <button type="button" class="btn btn-link btn-sm ms-2 select-all-btn" data-resource="{{ $resource }}">
                            Select All
                        </button>
                        @endif
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
                            $isChecked = $role->name === 'super-admin' || in_array($permission->name, old('permissions', $rolePermissions));
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="perm_{{ $permission->id }}"
                                       data-resource="{{ $resource }}"
                                       {{ $isChecked ? 'checked' : '' }}
                                       {{ $role->name === 'super-admin' ? 'disabled' : '' }}>
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
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Role Info</h5>

                @php
                    $roleColors = [
                        'super-admin' => 'danger',
                        'admin' => 'primary',
                        'content-manager' => 'info',
                        'question-editor' => 'success',
                        'student' => 'secondary',
                    ];
                @endphp

                <div class="text-center mb-3">
                    <div class="rounded-circle bg-{{ $roleColors[$role->name] ?? 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2"
                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="mb-1">{{ ucwords(str_replace('-', ' ', $role->name)) }}</h5>
                    <span class="badge bg-light text-dark">{{ $role->name }}</span>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Users with this role:</span>
                        <strong>{{ $role->users()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Permissions:</span>
                        <strong>{{ $role->name === 'super-admin' ? 'All' : count($rolePermissions) }}</strong>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Role
                    </button>
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-info">
                        <i class="bi bi-people me-2"></i>View Users
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Roles
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
        const checkboxes = document.querySelectorAll(`.permission-checkbox[data-resource="${resource}"]:not(:disabled)`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);

        checkboxes.forEach(cb => cb.checked = !allChecked);
        this.textContent = allChecked ? 'Select All' : 'Deselect All';
    });
});
</script>
@endpush
