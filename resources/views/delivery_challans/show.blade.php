@extends('layouts.app')

@section('content')
    @php
        $challanDisplayNumber = (int) ltrim((string) preg_replace('/[^0-9]/', '', (string) $deliveryChallan->challan_number), '0');
        if ($challanDisplayNumber === 0) {
            $challanDisplayNumber = (int) $deliveryChallan->id;
        }
    @endphp

    <style>
        .meter-grid-preview {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .meter-col-preview {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .meter-col-preview .col-head {
            background: #f8f9fa;
            font-weight: 600;
            padding: 0.45rem 0.6rem;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.86rem;
        }

        .meter-line-preview {
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.35rem 0.6rem;
            border-bottom: 1px solid #f1f3f5;
            font-size: 0.84rem;
        }

        .meter-line-preview:last-child {
            border-bottom: none;
        }

        @media (max-width: 991px) {
            .meter-grid-preview {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575px) {
            .meter-grid-preview {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="app-page-header">
        <h4 class="mb-0">Delivery Challan {{ $deliveryChallan->challan_number }}</h4>
        <div class="app-page-actions">
            <a href="{{ route('delivery-challans.preview', $deliveryChallan) }}" class="btn btn-info">Preview</a>
            <a href="{{ route('delivery-challans.download', $deliveryChallan) }}" class="btn btn-primary">Download PDF</a>
            <a href="{{ route('delivery-challans.edit', $deliveryChallan) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('delivery-challans.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Challan No:</strong> {{ $challanDisplayNumber }}</div>
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
            <div class="meter-grid-preview mb-3">
                @php
                    $metersBySr = $deliveryChallan->meters->keyBy('sr_no');
                @endphp

                @for($column = 0; $column < 4; $column++)
                    @php
                        $startSr = ($column * 12) + 1;
                        $endSr = $startSr + 11;
                    @endphp
                    <div class="meter-col-preview">
                        <div class="col-head">Sr {{ $startSr }} - {{ $endSr }}</div>
                        @for($sr = $startSr; $sr <= $endSr; $sr++)
                            @php
                                $meterRow = $metersBySr->get($sr);
                            @endphp
                            <div class="meter-line-preview">
                                <span><strong>{{ $sr }}</strong></span>
                                <span>{{ $meterRow ? number_format((float) $meterRow->meter, 2) : '-' }}</span>
                            </div>
                        @endfor
                    </div>
                @endfor
            </div>
            @if($deliveryChallan->remark)
                <div><strong>Remark:</strong> {{ $deliveryChallan->remark }}</div>
            @endif
        </div>
    </div>
@endsection
