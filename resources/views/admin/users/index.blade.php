@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'User Management')
@section('page-actions')
    @can('create users')
    <a href="{{ route('admin.users.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New User
    </a>
    @endcan
@endsection

@section('main')
<x-card class="mb-4">
    <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
        <!-- Basic Filters Row -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="search" class="form-label small text-muted">Search</label>
                <input type="text"
                       class="form-control"
                       name="search"
                       id="search"
                       placeholder="Search by name or email..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label small text-muted">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="all">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="subscription" class="form-label small text-muted">Subscription</label>
                <select name="subscription" id="subscription" class="form-select">
                    <option value="all">All</option>
                    <option value="free" {{ request('subscription') === 'free' ? 'selected' : '' }}>Free</option>
                    <option value="premium" {{ request('subscription') === 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="expiring" {{ request('subscription') === 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                    <option value="expired" {{ request('subscription') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label small text-muted">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="all">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                    <i class="bi bi-sliders me-1"></i>More
                </button>
            </div>
        </div>

        <!-- Advanced Filters (Collapsible) -->
        <div class="collapse {{ request()->hasAny(['course', 'date_from', 'date_to', 'verified']) ? 'show' : '' }}" id="advancedFilters">
            <div class="row g-3 pt-3 border-top">
                <div class="col-md-2">
                    <label for="course" class="form-label small text-muted">Enrolled Course</label>
                    <select name="course" id="course" class="form-select">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                                {{ $course->code ? $course->code . ' - ' : '' }}{{ Str::limit($course->title, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="verified" class="form-label small text-muted">Verified</label>
                    <select name="verified" id="verified" class="form-select">
                        <option value="all">All</option>
                        <option value="yes" {{ request('verified') === 'yes' ? 'selected' : '' }}>Verified</option>
                        <option value="no" {{ request('verified') === 'no' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label small text-muted">Registered From</label>
                    <input type="date" class="form-control" name="date_from" id="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label small text-muted">Registered To</label>
                    <input type="date" class="form-control" name="date_to" id="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label small text-muted">Sort By</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Date Joined</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="direction" class="form-label small text-muted">Order</label>
                    <select name="direction" id="direction" class="form-select">
                        <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Filter Action Buttons -->
        <div class="row mt-3">
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Apply Filters
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Clear Filters
                </a>
                @if(request()->hasAny(['search', 'role', 'subscription', 'verified', 'course', 'date_from', 'date_to']) && request('role') !== 'all')
                <span class="badge bg-info d-flex align-items-center ms-2">
                    {{ $users->total() }} result(s)
                </span>
                @endif
            </div>
        </div>
    </form>
</x-card>

<x-card>

    <!-- Bulk Actions Bar -->
    @canany(['edit users', 'delete users'])
    <div id="bulkActionsBar" class="alert alert-primary d-none mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <i class="bi bi-check2-square me-2"></i>
                <span id="selectedCount">0</span> user(s) selected
                <small class="text-muted ms-2">(Super Admins are protected and will be skipped)</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @can('edit users')
                <div class="dropdown">
                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-gear me-1"></i>Assign Role
                    </button>
                    <ul class="dropdown-menu">
                        @foreach($roles as $role)
                            @if($role->name !== 'super-admin')
                            <li>
                                <a class="dropdown-item" href="#" onclick="bulkAction('assign_role', '{{ $role->name }}')">
                                    {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn btn-warning btn-sm" onclick="bulkAction('block')">
                    <i class="bi bi-slash-circle me-1"></i>Block
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('unblock')">
                    <i class="bi bi-unlock me-1"></i>Unblock
                </button>
                @endcan
                @can('delete users')
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('delete')">
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
                @endcan
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </button>
            </div>
        </div>
    </div>
    @endcanany

    <div class="table-responsive">
        <table class="table-modern table align-middle mb-0">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" class="form-check-input" id="selectAll" title="Select All">
                    </th>
                    <th width="22%">User</th>
                    <th width="18%">Email</th>
                    <th class="text-center" width="10%">Role</th>
                    <th class="text-center" width="12%">Subscription</th>
                    <th class="text-center" width="10%">Joined</th>
                    <th class="text-end" width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}"
                               data-name="{{ $user->name }}" data-is-self="{{ $user->id === auth()->id() ? 'true' : 'false' }}"
                               @if($user->id === auth()->id()) disabled @endif>
                    </td>
                    <td>
                        <div class="d-flex align-items-center" style="max-width: 100%;">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}"
                                     alt="{{ $user->name }}"
                                     class="rounded-circle me-2 flex-shrink-0"
                                     style="width: 36px; height: 36px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                     style="width: 36px; height: 36px; min-width: 36px; min-height: 36px; font-size: 13px; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <div style="min-width: 0; flex: 1;">
                                <div class="fw-medium text-truncate" style="max-width: 100%;">{{ $user->name }}</div>
                                @if($user->email_verified_at)
                                    <small class="text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </small>
                                @else
                                    <small class="text-warning">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $user->email }}</div>
                    </td>
                    <td class="text-center">
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
                        @endphp
                        @forelse($user->roles as $role)
                            <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }}" style="font-size: 0.7rem;" title="{{ ucwords(str_replace('-', ' ', $role->name)) }}">
                                <i class="bi {{ $roleIcons[$role->name] ?? 'bi-person' }}"></i>
                            </span>
                        @empty
                            <span class="badge bg-light text-muted" style="font-size: 0.7rem;">
                                <i class="bi bi-person"></i>
                            </span>
                        @endforelse
                    </td>
                    <td class="text-center">
                        @if($user->is_blocked)
                            <span class="badge bg-danger" style="font-size: 0.75rem;" title="Blocked{{ $user->blocked_at ? ' on ' . $user->blocked_at->format('M d, Y') : '' }}">
                                <i class="bi bi-slash-circle"></i> Blocked
                            </span>
                        @elseif($user->isPremium())
                            <span class="badge bg-warning text-dark" style="font-size: 0.75rem;">
                                <i class="bi bi-star-fill"></i> Premium
                            </span>
                            @php
                                $activeSubscription = $user->subscriptions()->where('status', 'active')->where('expires_at', '>', now())->first();
                                $expiryDate = $activeSubscription ? $activeSubscription->expires_at : $user->subscription_expires_at;
                            @endphp
                            @if($expiryDate)
                                <div class="text-muted" style="font-size: 0.7rem;">
                                    {{ $expiryDate->format('M d') }}
                                </div>
                            @endif
                        @else
                            <span class="badge bg-secondary" style="font-size: 0.75rem;">Free</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="text-muted small">{{ $user->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            {{-- Super Admin users have a lock icon - they cannot be modified --}}
                            @if($user->hasRole('super-admin') && $user->id !== auth()->id())
                                <span class="btn btn-sm btn-light" title="Protected Super Admin Account">
                                    <i class="bi bi-shield-lock text-danger"></i>
                                </span>
                            @else
                                {{-- Impersonate button - only for non-admin users --}}
                                @can('impersonate users')
                                @if(!$user->hasRole('super-admin') && !$user->hasAnyRole(['admin', 'content-manager', 'question-editor']))
                                <form action="{{ route('admin.users.impersonate', $user) }}"
                                      method="POST"
                                      class="d-inline impersonate-form">
                                    @csrf
                                    <button type="button"
                                            class="btn btn-sm btn-light impersonate-btn"
                                            title="Login as this user"
                                            data-name="{{ $user->name }}">
                                        <i class="bi bi-box-arrow-in-right text-info"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan

                                {{-- View button --}}
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-light"
                                   title="View">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>

                                {{-- Edit button - only if user can edit and target is not another super-admin --}}
                                @can('edit users')
                                @if(!$user->hasRole('super-admin') || $user->id === auth()->id())
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-light"
                                   title="Edit">
                                    <i class="bi bi-pencil text-secondary"></i>
                                </a>
                                @endif
                                @endcan

                                {{-- Block/Unblock buttons - not for super-admins or self --}}
                                @can('edit users')
                                @if($user->id !== auth()->id() && !$user->hasRole('super-admin'))
                                    @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user) }}"
                                          method="POST"
                                          class="d-inline unblock-form">
                                        @csrf
                                        <button type="button"
                                                class="btn btn-sm btn-light unblock-btn"
                                                title="Unblock user"
                                                data-name="{{ $user->name }}">
                                            <i class="bi bi-unlock text-success"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button type="button"
                                            class="btn btn-sm btn-light block-btn"
                                            title="Block user"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}">
                                        <i class="bi bi-slash-circle text-warning"></i>
                                    </button>
                                    @endif
                                @endif
                                @endcan

                                {{-- Delete button - not for super-admins or self --}}
                                @can('delete users')
                                @if(!$user->hasRole('super-admin'))
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-light delete-btn"
                                            title="Delete"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-is-self="{{ $user->id === auth()->id() ? 'true' : 'false' }}"
                                            @if($user->id === auth()->id()) disabled @endif>
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-people display-3 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                        <p class="text-muted mb-3">Try adjusting your filters or search terms</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</x-card>
@endsection

@push('scripts')
<script>
// Impersonate handler
document.querySelectorAll('.impersonate-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.impersonate-form');
        const name = this.dataset.name;

        Swal.fire({
            title: 'Login as User?',
            html: `You are about to login as <strong>"${name}"</strong>.<br><br>You will be able to view the site exactly as they see it.<br><br><span class="text-info"><i class="bi bi-info-circle"></i> A banner will appear at the top to return to your admin account.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-box-arrow-in-right me-2"></i>Yes, Login as User',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Single delete handler
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;
        const email = this.dataset.email;
        const isSelf = this.dataset.isSelf === 'true';

        if (isSelf) {
            Swal.fire({
                title: 'Cannot Delete',
                text: 'You cannot delete your own account!',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete user:<br><strong>"${name}"</strong><br>(${email})<br><br>This action cannot be undone.`,
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

// Block user handler
document.querySelectorAll('.block-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const userId = this.dataset.id;
        const name = this.dataset.name;
        const email = this.dataset.email;

        Swal.fire({
            title: 'Block User?',
            html: `You are about to block:<br><strong>"${name}"</strong><br>(${email})<br><br>This user will be logged out and unable to access the website.`,
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Reason for blocking (optional)',
            inputPlaceholder: 'Enter reason...',
            inputAttributes: {
                'aria-label': 'Reason for blocking'
            },
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-slash-circle me-2"></i>Yes, Block User',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/users') }}/${userId}/block`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                if (result.value) {
                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'reason';
                    reasonInput.value = result.value;
                    form.appendChild(reasonInput);
                }

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

// Unblock user handler
document.querySelectorAll('.unblock-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.unblock-form');
        const name = this.dataset.name;

        Swal.fire({
            title: 'Unblock User?',
            html: `Are you sure you want to unblock <strong>"${name}"</strong>?<br><br>This user will be able to log in and access the website again.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-unlock me-2"></i>Yes, Unblock User',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Bulk selection handling
const selectAllCheckbox = document.getElementById('selectAll');
const userCheckboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');
const bulkActionsBar = document.getElementById('bulkActionsBar');
const selectedCountSpan = document.getElementById('selectedCount');

function updateBulkActionsBar() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const count = checkedBoxes.length;

    selectedCountSpan.textContent = count;

    if (count > 0) {
        bulkActionsBar.classList.remove('d-none');
    } else {
        bulkActionsBar.classList.add('d-none');
    }

    // Update select all checkbox state
    if (userCheckboxes.length > 0) {
        selectAllCheckbox.checked = count === userCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < userCheckboxes.length;
    }
}

selectAllCheckbox?.addEventListener('change', function() {
    userCheckboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsBar();
});

userCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkActionsBar);
});

function clearSelection() {
    userCheckboxes.forEach(cb => cb.checked = false);
    selectAllCheckbox.checked = false;
    updateBulkActionsBar();
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
}

function getSelectedNames() {
    return Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.dataset.name);
}

function bulkAction(action, role = null) {
    const ids = getSelectedIds();
    const names = getSelectedNames();

    if (ids.length === 0) {
        Swal.fire('No Selection', 'Please select at least one user.', 'warning');
        return;
    }

    let title, text, confirmText, confirmColor, icon;

    switch (action) {
        case 'assign_role':
            const roleName = role.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const isAdmin = ['super-admin', 'admin'].includes(role);
            title = `Assign ${roleName} Role?`;
            text = `Are you sure you want to assign the <strong>${roleName}</strong> role to ${ids.length} user(s)?`;
            if (isAdmin) {
                text += `<br><br><strong class="text-warning"><i class="bi bi-exclamation-triangle"></i> This role has elevated privileges!</strong>`;
            }
            confirmText = `<i class="bi bi-person-gear me-1"></i>Yes, Assign Role`;
            confirmColor = isAdmin ? '#dc3545' : '#0dcaf0';
            icon = isAdmin ? 'warning' : 'question';
            break;
        case 'block':
            title = 'Block Users?';
            text = `You are about to block ${ids.length} user(s):<br><ul class="text-start">${names.map(n => `<li>${n}</li>`).join('')}</ul><br>These users will be logged out and unable to access the website.`;
            confirmText = '<i class="bi bi-slash-circle me-1"></i>Yes, Block All';
            confirmColor = '#ffc107';
            icon = 'warning';
            break;
        case 'unblock':
            title = 'Unblock Users?';
            text = `You are about to unblock ${ids.length} user(s):<br><ul class="text-start">${names.map(n => `<li>${n}</li>`).join('')}</ul><br>These users will be able to access the website again.`;
            confirmText = '<i class="bi bi-unlock me-1"></i>Yes, Unblock All';
            confirmColor = '#198754';
            icon = 'question';
            break;
        case 'delete':
            title = 'Delete Users?';
            text = `You are about to delete ${ids.length} user(s):<br><ul class="text-start">${names.map(n => `<li>${n}</li>`).join('')}</ul><br><strong class="text-danger">This action cannot be undone!</strong>`;
            confirmText = '<i class="bi bi-trash me-1"></i>Yes, Delete All';
            confirmColor = '#dc3545';
            icon = 'warning';
            break;
    }

    Swal.fire({
        title: title,
        html: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit via form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.users.bulk-action") }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            if (role) {
                const roleInput = document.createElement('input');
                roleInput.type = 'hidden';
                roleInput.name = 'role';
                roleInput.value = role;
                form.appendChild(roleInput);
            }

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
