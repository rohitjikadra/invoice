@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Settings</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf

                <h5 class="mb-3">Company</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Company Name (Heading)</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Email</label>
                        <input type="email" name="company_email" value="{{ old('company_email', $settings->company_email) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Mobile</label>
                        <input type="text" name="company_mobile" value="{{ old('company_mobile', $settings->company_mobile) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Agent Name</label>
                        <input type="text" name="agent_name" value="{{ old('agent_name', $settings->agent_name) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Company Address</label>
                        <textarea name="company_address" rows="3" class="form-control">{{ old('company_address', $settings->company_address) }}</textarea>
                    </div>
                </div>

                <h5 class="mb-3">Tax / State</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">GST No</label>
                        <input type="text" name="gst_no" value="{{ old('gst_no', $settings->gst_no) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PAN No</label>
                        <input type="text" name="pan_no" value="{{ old('pan_no', $settings->pan_no) }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">State Name</label>
                        <input type="text" name="state_name" value="{{ old('state_name', $settings->state_name) }}" class="form-control" placeholder="GUJARAT">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">State Code</label>
                        <input type="text" name="state_code" value="{{ old('state_code', $settings->state_code) }}" class="form-control" placeholder="24">
                    </div>
                </div>

                <h5 class="mb-3">Bank Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Account No</label>
                        <input type="text" name="bank_account_no" value="{{ old('bank_account_no', $settings->bank_account_no) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">IFSC</label>
                        <input type="text" name="bank_ifsc" value="{{ old('bank_ifsc', $settings->bank_ifsc) }}" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Branch</label>
                        <input type="text" name="bank_branch" value="{{ old('bank_branch', $settings->bank_branch) }}" class="form-control">
                    </div>
                </div>

                <h5 class="mb-3">Terms &amp; Conditions</h5>
                <div class="mb-4">
                    <textarea name="terms" rows="6" class="form-control">{{ old('terms', $settings->terms) }}</textarea>
                    <div class="form-text">This text will be printed on the PDF.</div>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
@endsection
