@extends('layouts.admin')

@section('page-header', true)
@section('page-title', 'Notification Preferences')

@section('page-actions')
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Notifications
    </a>
@endsection

@section('main')
<div class="row">
    <div class="col-lg-8">
        <x-card title="Manage Your Notifications">
            <form action="{{ route('admin.notifications.preferences.update') }}" method="POST">
                @csrf
                @method('PUT')

                <p class="text-muted mb-4">
                    Choose how you want to receive notifications. You can enable or disable in-app and email notifications for each type.
                </p>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Notification Type</th>
                                <th class="text-center" style="width: 120px;">In-App</th>
                                <th class="text-center" style="width: 120px;">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preferences as $type => $pref)
                                <tr>
                                    <td>
                                        <strong>{{ $pref['label'] }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="preferences[{{ $type }}][in_app]"
                                                   {{ $pref['in_app'] ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="preferences[{{ $type }}][email]"
                                                   {{ $pref['email'] ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i> Save Preferences
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <div class="col-lg-4">
        <x-card title="About Notifications">
            <div class="text-muted small">
                <p><strong>In-App Notifications</strong></p>
                <p>These appear in your notification bell and on the notifications page. They're stored in your account and you can view them anytime.</p>

                <p class="mt-3"><strong>Email Notifications</strong></p>
                <p>Important updates sent directly to your email address. Perfect for staying informed even when you're not logged in.</p>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    You'll always receive critical system notifications regardless of these settings.
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
