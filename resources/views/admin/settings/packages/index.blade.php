@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Subscription Packages')
@section('page-actions')
    <a href="{{ route('admin.settings.packages.create') }}" class="btn-modern btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Package
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-12">
        <x-card>
            @if($packages->count() > 0)
            <div class="table-responsive">
                <table class="table-modern table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Popular</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $package->name }}</div>
                                @if($package->description)
                                    <small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ $package->formatted_price }}</span>
                            </td>
                            <td>{{ $package->duration_text }}</td>
                            <td class="text-center">
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($package->is_popular)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill"></i> Popular
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('admin.settings.packages.edit', $package) }}"
                                       class="btn btn-sm btn-light"
                                       title="Edit">
                                        <i class="bi bi-pencil text-secondary"></i>
                                    </a>
                                    <form action="{{ route('admin.settings.packages.destroy', $package) }}"
                                          method="POST"
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-light delete-btn"
                                                title="Delete"
                                                data-name="{{ $package->name }}">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                <h5 class="text-muted mb-3">No Subscription Packages</h5>
                <p class="text-muted mb-4">Create your first subscription package to start accepting payments.</p>
                <a href="{{ route('admin.settings.packages.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Package
                </a>
            </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Quick Stats -->
@if($packages->count() > 0)
<div class="row mt-4">
    <div class="col-md-4">
        <x-stat-card
            title="Total Packages"
            :value="$packages->count()"
            icon="box-seam"
            color="primary"
        />
    </div>
    <div class="col-md-4">
        <x-stat-card
            title="Active Packages"
            :value="$packages->where('is_active', true)->count()"
            icon="check-circle"
            color="success"
        />
    </div>
    <div class="col-md-4">
        <x-stat-card
            title="Price Range"
            :value="'KES ' . number_format($packages->min('price')) . ' - ' . number_format($packages->max('price'))"
            icon="currency-dollar"
            color="info"
        />
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        const name = this.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete the package <strong>"${name}"</strong>.<br><br>This action cannot be undone.`,
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
