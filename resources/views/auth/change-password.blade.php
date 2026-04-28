@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Change password</h4>
        <div class="app-page-actions">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>

    <div class="card shadow-sm" style="max-width: 520px;">
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Current password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">New password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>

                <div class="app-submit-bar">
                    <button type="submit" class="btn btn-primary w-100 w-lg-auto">Update password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
