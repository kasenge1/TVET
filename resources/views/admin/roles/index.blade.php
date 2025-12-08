@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Role Management')
@section('page-actions')
    <a href="{{ route('admin.roles.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Create New Role
    </a>
@endsection

@section('main')
<x-card>
    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="25%">Role Name</th>
                    <th width="35%">Permissions</th>
                    <th class="text-center" width="15%">Users</th>
                    <th class="text-center" width="10%">Status</th>
                    <th class="text-end" width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                @php
                    $roleColors = [
                        'super-admin' => 'danger',
                        'admin' => 'primary',
                        'content-manager' => 'info',
                        'question-editor' => 'success',
                        'student' => 'secondary',
                    ];
                    $roleIcons = [
                        'super-admin' => 'bi-shield-shaded',
                        'admin' => 'bi-shield-fill',
                        'content-manager' => 'bi-folder-fill',
                        'question-editor' => 'bi-pencil-fill',
                        'student' => 'bi-person-fill',
                    ];
                    $isProtected = in_array($role->name, ['super-admin', 'admin', 'student']);
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-{{ $roleColors[$role->name] ?? 'secondary' }} text-white d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                <i class="bi {{ $roleIcons[$role->name] ?? 'bi-person-badge' }}"></i>
                            </div>
                            <div>
                                <div class="fw-medium">{{ ucwords(str_replace('-', ' ', $role->name)) }}</div>
                                <small class="text-muted">{{ $role->name }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($role->name === 'super-admin')
                            <span class="badge bg-danger">All Permissions</span>
                        @elseif($role->permissions_count > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($role->permissions->take(4) as $permission)
                                    <span class="badge bg-light text-dark" style="font-size: 0.7rem;">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                                @if($role->permissions_count > 4)
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                        +{{ $role->permissions_count - 4 }} more
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">No permissions</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.roles.show', $role) }}" class="text-decoration-none">
                            <span class="badge bg-light text-dark" style="font-size: 0.85rem;">
                                <i class="bi bi-people me-1"></i>{{ $role->users_count }}
                            </span>
                        </a>
                    </td>
                    <td class="text-center">
                        @if($isProtected)
                            <span class="badge bg-warning text-dark" style="font-size: 0.75rem;">
                                <i class="bi bi-lock-fill me-1"></i>System
                            </span>
                        @else
                            <span class="badge bg-success" style="font-size: 0.75rem;">
                                <i class="bi bi-check-circle me-1"></i>Custom
                            </span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.roles.show', $role) }}"
                               class="btn btn-sm btn-light"
                               title="View Users">
                                <i class="bi bi-eye text-primary"></i>
                            </a>
                            <a href="{{ route('admin.roles.edit', $role) }}"
                               class="btn btn-sm btn-light"
                               title="Edit Permissions">
                                <i class="bi bi-pencil text-secondary"></i>
                            </a>
                            @if(!$isProtected)
                            <form action="{{ route('admin.roles.destroy', $role) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-light delete-btn"
                                        title="Delete"
                                        data-name="{{ ucwords(str_replace('-', ' ', $role->name)) }}"
                                        data-users="{{ $role->users_count }}">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-shield display-3 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No roles found</h5>
                        <p class="text-muted mb-3">Create your first role to get started</p>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Role
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

<!-- Role Descriptions -->
<h5 class="mt-4 mb-3"><i class="bi bi-info-circle me-2"></i>Role Descriptions</h5>
<div class="row g-3">
    <!-- Super Admin Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-shaded fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Super Admin</h6>
                        <span class="badge bg-danger-subtle text-danger" style="font-size: 0.7rem;">Highest Access</span>
                    </div>
                </div>
                <p class="text-muted small mb-0">Full system access including settings, user impersonation, and all administrative functions.</p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">All Permissions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Admin</h6>
                        <span class="badge bg-primary-subtle text-primary" style="font-size: 0.7rem;">Full Management</span>
                    </div>
                </div>
                <p class="text-muted small mb-0">Manage courses, units, questions, and users. Cannot access sensitive system settings.</p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Courses</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Units</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Questions</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Manager Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-folder-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Content Manager</h6>
                        <span class="badge bg-info-subtle text-info" style="font-size: 0.7rem;">Content Control</span>
                    </div>
                </div>
                <p class="text-muted small mb-0">Create and manage content including courses, units, and questions.</p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Courses</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Units</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Questions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Editor Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-pencil-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Question Editor</h6>
                        <span class="badge bg-success-subtle text-success" style="font-size: 0.7rem;">Limited Access</span>
                    </div>
                </div>
                <p class="text-muted small mb-0">Add and edit questions only. Ideal for assistants helping with content creation.</p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">View Courses</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">View Units</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Create/Edit Questions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Student</h6>
                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">Frontend Only</span>
                    </div>
                </div>
                <p class="text-muted small mb-0">Regular user with access to learning content and practice tests.</p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Learn</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Practice</span>
                    <span class="badge bg-light text-dark" style="font-size: 0.65rem;">Bookmarks</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Role Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm border-dashed" style="border: 2px dashed #dee2e6 !important;">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-plus-lg fs-5"></i>
                </div>
                <h6 class="mb-2 fw-bold text-muted">Create Custom Role</h6>
                <p class="text-muted small mb-3">Define your own role with specific permissions tailored to your needs.</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Create Role
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;
        const users = parseInt(this.dataset.users);

        if (users > 0) {
            Swal.fire({
                title: 'Cannot Delete',
                html: `The role <strong>"${name}"</strong> has <strong>${users} user(s)</strong> assigned to it.<br><br>Please reassign or remove users from this role first.`,
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Delete Role?',
            html: `Are you sure you want to delete the role <strong>"${name}"</strong>?<br><br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
