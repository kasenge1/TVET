@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Role: ' . ucwords(str_replace('-', ' ', $role->name)))
@section('page-actions')
    <a href="{{ route('admin.roles.edit', $role) }}" class="btn-modern btn btn-primary">
        <i class="bi bi-pencil me-2"></i>Edit Permissions
    </a>
@endsection

@section('main')
<div class="row">
    <!-- Role Info -->
    <div class="col-lg-4">
        <x-card class="mb-4">
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

            <div class="text-center mb-4">
                <div class="rounded-circle bg-{{ $roleColors[$role->name] ?? 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi {{ $roleIcons[$role->name] ?? 'bi-person-badge' }}"></i>
                </div>
                <h4 class="mb-1">{{ ucwords(str_replace('-', ' ', $role->name)) }}</h4>
                <span class="badge bg-light text-dark">{{ $role->name }}</span>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Users:</span>
                    <strong>{{ $users->total() }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Permissions:</span>
                    <strong>{{ $role->name === 'super-admin' ? 'All' : $role->permissions->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Type:</span>
                    @if(in_array($role->name, ['super-admin', 'admin', 'student']))
                        <span class="badge bg-warning text-dark">System Role</span>
                    @else
                        <span class="badge bg-success">Custom Role</span>
                    @endif
                </div>
            </div>
        </x-card>

        <x-card>
            <h6 class="mb-3"><i class="bi bi-key me-2"></i>Permissions</h6>

            @if($role->name === 'super-admin')
                <div class="alert alert-danger small mb-0">
                    <i class="bi bi-shield-shaded me-2"></i>
                    Super Admin has <strong>all permissions</strong>
                </div>
            @elseif($role->permissions->count() > 0)
                <div class="d-flex flex-wrap gap-1">
                    @foreach($role->permissions as $permission)
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
                            ];
                        @endphp
                        <span class="badge bg-{{ $actionColors[$action] ?? 'secondary' }}" style="font-size: 0.7rem;">
                            {{ $permission->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-0">No permissions assigned</p>
            @endif
        </x-card>
    </div>

    <!-- Users with this role -->
    <div class="col-lg-8">
        <x-card>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Users with this Role</h5>
                <span class="badge bg-primary">{{ $users->total() }} user(s)</span>
            </div>

            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table-modern table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th class="text-center">Other Roles</th>
                            <th class="text-center">Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->profile_photo_url)
                                        <img src="{{ $user->profile_photo_url }}"
                                             alt="{{ $user->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                             style="width: 36px; height: 36px; font-size: 13px; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small">{{ $user->email }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $otherRoles = $user->roles->where('name', '!=', $role->name);
                                @endphp
                                @if($otherRoles->count() > 0)
                                    @foreach($otherRoles as $otherRole)
                                        <span class="badge bg-{{ $roleColors[$otherRole->name] ?? 'secondary' }}" style="font-size: 0.7rem;">
                                            {{ ucwords(str_replace('-', ' ', $otherRole->name)) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-light"
                                   title="Edit User">
                                    <i class="bi bi-pencil text-secondary"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="bi bi-people display-3 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No users with this role</h5>
                <p class="text-muted mb-3">Assign this role to users from the user management page</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="bi bi-people me-2"></i>Manage Users
                </a>
            </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
