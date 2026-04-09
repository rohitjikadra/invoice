@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Delivery Challan {{ $deliveryChallan->challan_number }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('delivery-challans.preview', $deliveryChallan) }}" class="btn btn-info">Preview</a>
            <a href="{{ route('delivery-challans.download', $deliveryChallan) }}" class="btn btn-primary">Download PDF</a>
            <a href="{{ route('delivery-challans.edit', $deliveryChallan) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('delivery-challans.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Challan No:</strong> {{ $deliveryChallan->challan_number }}</div>
                <div class="col-md-4"><strong>Date:</strong> {{ $deliveryChallan->challan_date?->format('Y-m-d') }}</div>
                <div class="col-md-4"><strong>Vehicle No:</strong> {{ $deliveryChallan->vehicle_no ?: '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Eway Bill:</strong> {{ $deliveryChallan->eway_bill_no ?: '-' }}</div>
                <div class="col-md-4"><strong>Quality:</strong> {{ $deliveryChallan->quality ?: '-' }}</div>
                <div class="col-md-4"><strong>Broker:</strong> {{ $deliveryChallan->broker ?: '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Receiver</h6>
                    <div>{{ $deliveryChallan->receiver_name }}</div>
                    <div>{{ $deliveryChallan->receiver_address }}</div>
                    <div>GSTIN: {{ $deliveryChallan->receiver_gstin ?: '-' }}</div>
                </div>
                <div class="col-md-6">
                    <h6>Consignee</h6>
                    <div>{{ $deliveryChallan->consignee_name }}</div>
                    <div>{{ $deliveryChallan->consignee_address }}</div>
                    <div>GSTIN: {{ $deliveryChallan->consignee_gstin ?: '-' }}</div>
                </div>
            </div>

            <h6>Meter Entries</h6>
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                    <tr><th>Sr</th><th>Meter</th></tr>
                    </thead>
                    <tbody>
                    @foreach($deliveryChallan->meters as $meter)
                        <tr>
                            <td>{{ $meter->sr_no }}</td>
                            <td>{{ number_format((float) $meter->meter, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($deliveryChallan->remark)
                <div><strong>Remark:</strong> {{ $deliveryChallan->remark }}</div>
            @endif
        </div>
    </div>
@endsection
