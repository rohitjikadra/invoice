@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 180px);">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Admin Login</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
