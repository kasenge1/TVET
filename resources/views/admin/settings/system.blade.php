@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'System Settings')

@section('main')
<div class="row">
    <div class="col-xl-8">
        <!-- Cache Management -->
        <x-card title="Cache Management" class="border-primary mb-4">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Clear various caches to apply changes or resolve issues. This is safe to do at any time.
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-database fs-5"></i>
                            </div>
                            <h6>Application Cache</h6>
                            <p class="text-muted small mb-3">Clears the general application cache</p>
                            <form action="{{ route('admin.settings.cache.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-trash me-1"></i>Clear Cache
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-gear fs-5"></i>
                            </div>
                            <h6>Config Cache</h6>
                            <p class="text-muted small mb-3">Clears cached configuration files</p>
                            <form action="{{ route('admin.settings.config.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-trash me-1"></i>Clear Config
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-signpost-split fs-5"></i>
                            </div>
                            <h6>Route Cache</h6>
                            <p class="text-muted small mb-3">Clears cached route definitions</p>
                            <form action="{{ route('admin.settings.routes.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-trash me-1"></i>Clear Routes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-arrow-clockwise fs-5"></i>
                            </div>
                            <h6>Clear All</h6>
                            <p class="text-muted small mb-3">Clears all caches at once</p>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllCaches()">
                                <i class="bi bi-trash me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- System Information -->
        <x-card title="System Information" class="border-secondary">
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-medium" width="40%">PHP Version</td>
                            <td><span class="badge bg-primary">{{ PHP_VERSION }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Laravel Version</td>
                            <td><span class="badge bg-danger">{{ app()->version() }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Environment</td>
                            <td>
                                <span class="badge {{ app()->environment('production') ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst(app()->environment()) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Debug Mode</td>
                            <td>
                                <span class="badge {{ config('app.debug') ? 'bg-danger' : 'bg-success' }}">
                                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Server Software</td>
                            <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Database</td>
                            <td>
                                @php
                                    $dbConnection = config('database.default');
                                    $dbName = config("database.connections.{$dbConnection}.database");
                                @endphp
                                <span class="badge bg-info">{{ ucfirst($dbConnection) }}</span>
                                <span class="text-muted ms-1">{{ $dbName }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Cache Driver</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(config('cache.default')) }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Session Driver</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(config('session.driver')) }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Queue Driver</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(config('queue.default')) }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Timezone</td>
                            <td>{{ config('app.timezone') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Server Time</td>
                            <td>{{ now()->format('F d, Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <div class="col-xl-4">
        <x-card title="Quick Actions" class="border-secondary">
            <div class="d-grid gap-2">
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-clock-history me-2"></i>View Activity Logs
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-graph-up me-2"></i>View Analytics
                </a>
                <a href="{{ route('admin.settings.maintenance') }}" class="btn btn-outline-warning">
                    <i class="bi bi-tools me-2"></i>Maintenance Mode
                </a>
            </div>
        </x-card>

        <x-card title="Storage" class="mt-4">
            @php
                $totalSpace = disk_total_space(base_path());
                $freeSpace = disk_free_space(base_path());
                $usedSpace = $totalSpace - $freeSpace;
                $usedPercent = round(($usedSpace / $totalSpace) * 100, 1);

                $formatBytes = function($bytes) {
                    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                    $i = 0;
                    while ($bytes >= 1024 && $i < count($units) - 1) {
                        $bytes /= 1024;
                        $i++;
                    }
                    return round($bytes, 2) . ' ' . $units[$i];
                };
            @endphp

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Disk Usage</span>
                    <span class="fw-bold">{{ $usedPercent }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar {{ $usedPercent > 90 ? 'bg-danger' : ($usedPercent > 70 ? 'bg-warning' : 'bg-success') }}"
                         style="width: {{ $usedPercent }}%"></div>
                </div>
            </div>

            <div class="small">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Used</span>
                    <span>{{ $formatBytes($usedSpace) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Free</span>
                    <span>{{ $formatBytes($freeSpace) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Total</span>
                    <span>{{ $formatBytes($totalSpace) }}</span>
                </div>
            </div>
        </x-card>

        <x-card title="PHP Extensions" class="mt-4">
            <div class="small">
                @php
                    $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'json', 'curl', 'gd', 'xml', 'zip'];
                @endphp
                @foreach($requiredExtensions as $ext)
                    <div class="d-flex justify-content-between py-1">
                        <span>{{ $ext }}</span>
                        @if(extension_loaded($ext))
                            <span class="badge bg-success"><i class="bi bi-check"></i></span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x"></i></span>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
async function clearAllCaches() {
    const result = await Swal.fire({
        title: 'Clear All Caches?',
        text: 'This will clear application, config, and route caches.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, clear all!'
    });

    if (result.isConfirmed) {
        try {
            // Clear each cache sequentially
            await fetch('{{ route("admin.settings.cache.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            await fetch('{{ route("admin.settings.config.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            await fetch('{{ route("admin.settings.routes.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            Swal.fire({
                icon: 'success',
                title: 'All Caches Cleared!',
                text: 'Application, config, and route caches have been cleared.',
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                window.location.reload();
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to clear some caches. Please try again.'
            });
        }
    }
}
</script>
@endpush
@endsection
