@extends('install.layout')

@section('title', 'Database Configuration')
@section('step1-class', 'completed')
@section('step2-class', 'completed')
@section('step3-class', 'active')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-3">Database Configuration</h4>
    <p class="text-muted">
        Enter your MySQL database credentials. Make sure the database exists before proceeding.
    </p>
</div>

<form action="{{ route('install.database.store') }}" method="POST">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-md-8">
            <label for="db_host" class="form-label">Database Host</label>
            <input type="text"
                   class="form-control @error('db_host') is-invalid @enderror"
                   id="db_host"
                   name="db_host"
                   value="{{ old('db_host', 'localhost') }}"
                   placeholder="localhost"
                   required>
            @error('db_host')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="db_port" class="form-label">Port</label>
            <input type="number"
                   class="form-control @error('db_port') is-invalid @enderror"
                   id="db_port"
                   name="db_port"
                   value="{{ old('db_port', '3306') }}"
                   placeholder="3306"
                   required>
            @error('db_port')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="db_database" class="form-label">Database Name</label>
        <input type="text"
               class="form-control @error('db_database') is-invalid @enderror"
               id="db_database"
               name="db_database"
               value="{{ old('db_database') }}"
               placeholder="tvet_revision"
               required>
        @error('db_database')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">The database must already exist on your server.</div>
    </div>

    <div class="mb-3">
        <label for="db_username" class="form-label">Database Username</label>
        <input type="text"
               class="form-control @error('db_username') is-invalid @enderror"
               id="db_username"
               name="db_username"
               value="{{ old('db_username') }}"
               placeholder="root"
               required>
        @error('db_username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="db_password" class="form-label">Database Password</label>
        <input type="password"
               class="form-control @error('db_password') is-invalid @enderror"
               id="db_password"
               name="db_password"
               value="{{ old('db_password') }}"
               placeholder="Leave empty if no password">
        @error('db_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Tip:</strong> If using cPanel, your database name is usually <code>cpanelusername_dbname</code>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('install.requirements') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
        <button type="submit" class="btn btn-install">
            Test Connection & Continue<i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</form>
@endsection
