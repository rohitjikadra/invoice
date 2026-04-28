@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Create Customer</h4>
        <div class="app-page-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GST Number (Optional)</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number') }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                    </div>
                </div>
                <div class="mt-3 app-submit-bar">
                    <button type="submit" class="btn btn-primary w-100 w-lg-auto">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
@endsection
