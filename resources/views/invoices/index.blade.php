@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Invoices</h4>
        <div class="app-page-actions">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="invoice_number" value="{{ request('invoice_number') }}" class="form-control" placeholder="Invoice #">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['paid', 'unpaid', 'overdue'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark">Go</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm desktop-table-wrap d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer?->name }}</td>
                        <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                        <td>{{ $invoice->due_date?->format('Y-m-d') }}</td>
                        <td><span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning text-dark') }}">{{ strtoupper($invoice->status) }}</span></td>
                        <td>{{ number_format((float) $invoice->total_amount, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this invoice?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No invoices found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>

    <div class="mobile-card-wrap app-mobile-cards d-grid gap-2 d-lg-none">
        @forelse($invoices as $invoice)
            <div class="app-mobile-card">
                <div class="title">{{ $invoice->invoice_number }} - {{ $invoice->customer?->name }}</div>
                <div class="meta mb-1">Date: {{ $invoice->invoice_date?->format('Y-m-d') }} | Due: {{ $invoice->due_date?->format('Y-m-d') }}</div>
                <div class="meta mb-2">Status: {{ strtoupper($invoice->status) }} | Total: {{ number_format((float) $invoice->total_amount, 2) }}</div>
                <div class="app-mobile-actions">
                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Delete this invoice?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="app-mobile-card text-center">No invoices found.</div>
        @endforelse
        <div class="card shadow-sm">
            <div class="card-body">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
