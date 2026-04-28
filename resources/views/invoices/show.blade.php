@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Invoice {{ $invoice->invoice_number }}</h4>
        <div class="app-page-actions">
            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-primary">Download PDF (A4)</a>
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Customer:</strong> {{ $invoice->customer?->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $invoice->customer?->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $invoice->customer?->phone }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1"><strong>Invoice Date:</strong> {{ $invoice->invoice_date?->format('Y-m-d') }}</p>
                    <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date?->format('Y-m-d') }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ strtoupper($invoice->status) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th style="width: 120px;">Qty</th>
                    <th style="width: 150px;">Price</th>
                    <th style="width: 150px;">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ number_format((float) $item->quantity, 2) }}</td>
                        <td>{{ number_format((float) $item->price, 2) }}</td>
                        <td>{{ number_format((float) $item->total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-4">
            <table class="table table-sm">
                <tr><th>Subtotal</th><td class="text-end">{{ number_format((float) $invoice->subtotal, 2) }}</td></tr>
                <tr><th>Tax ({{ number_format((float) $invoice->tax_percentage, 2) }}%)</th><td class="text-end">{{ number_format((float) $invoice->tax_amount, 2) }}</td></tr>
                <tr><th>Total</th><td class="text-end fw-bold">{{ number_format((float) $invoice->total_amount, 2) }}</td></tr>
            </table>
        </div>
    </div>

    @if($invoice->notes)
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <strong>Notes:</strong>
                <p class="mb-0 mt-2">{{ $invoice->notes }}</p>
            </div>
        </div>
    @endif
@endsection
