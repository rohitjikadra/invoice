@extends('layouts.app')

@php
    $isEdit = isset($deliveryChallan);
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $isEdit ? 'Edit Delivery Challan' : 'Create Delivery Challan' }}</h4>
        <a href="{{ route('delivery-challans.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ $isEdit ? route('delivery-challans.update', $deliveryChallan) : route('delivery-challans.store') }}">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option
                                    value="{{ $customer->id }}"
                                    data-name="{{ $customer->name }}"
                                    data-address="{{ $customer->address }}"
                                    data-gstin="{{ $customer->gst_number }}"
                                    @selected((string) old('customer_id', $deliveryChallan->customer_id ?? '') === (string) $customer->id)
                                >{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Challan Date</label>
                        <input type="date" name="challan_date" value="{{ old('challan_date', isset($deliveryChallan) ? $deliveryChallan->challan_date?->toDateString() : now()->toDateString()) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vehicle No</label>
                        <input type="text" name="vehicle_no" value="{{ old('vehicle_no', $deliveryChallan->vehicle_no ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Eway Bill</label>
                        <input type="text" name="eway_bill_no" value="{{ old('eway_bill_no', $deliveryChallan->eway_bill_no ?? '') }}" class="form-control">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Quality</label>
                        <input type="text" name="quality" value="{{ old('quality', $deliveryChallan->quality ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Broker</label>
                        <input type="text" name="broker" value="{{ old('broker', $deliveryChallan->broker ?? '') }}" class="form-control">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <h6>Receiver Details</h6>
                        <div class="mb-2">
                            <input type="text" name="receiver_name" id="receiver_name" value="{{ old('receiver_name', $deliveryChallan->receiver_name ?? '') }}" class="form-control" placeholder="Receiver Name" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="receiver_address" id="receiver_address" class="form-control" rows="3" placeholder="Receiver Address" required>{{ old('receiver_address', $deliveryChallan->receiver_address ?? '') }}</textarea>
                        </div>
                        <input type="text" name="receiver_gstin" id="receiver_gstin" value="{{ old('receiver_gstin', $deliveryChallan->receiver_gstin ?? '') }}" class="form-control" placeholder="Receiver GSTIN">
                    </div>
                    <div class="col-md-6">
                        <h6>Consignee Details</h6>
                        <div class="mb-2">
                            <input type="text" name="consignee_name" id="consignee_name" value="{{ old('consignee_name', $deliveryChallan->consignee_name ?? '') }}" class="form-control" placeholder="Consignee Name" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="consignee_address" id="consignee_address" class="form-control" rows="3" placeholder="Consignee Address" required>{{ old('consignee_address', $deliveryChallan->consignee_address ?? '') }}</textarea>
                        </div>
                        <input type="text" name="consignee_gstin" id="consignee_gstin" value="{{ old('consignee_gstin', $deliveryChallan->consignee_gstin ?? '') }}" class="form-control" placeholder="Consignee GSTIN">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remark</label>
                    <textarea name="remark" class="form-control" rows="2">{{ old('remark', $deliveryChallan->remark ?? '') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Meter Entries</h6>
                    <button type="button" class="btn btn-sm btn-dark" id="addMeterBtn">+ Add Meter</button>
                </div>

                <div id="meterGrid" class="row g-2 mb-3">
                    @php
                        $oldMeters = old('meters');
                        $existingMeters = isset($deliveryChallan) ? $deliveryChallan->meters->pluck('meter')->toArray() : [];
                        $meters = $oldMeters ?? (count($existingMeters) ? $existingMeters : [0]);
                    @endphp
                    @foreach($meters as $i => $meter)
                        <div class="col-md-3 meter-entry">
                            <div class="border rounded p-2 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge text-bg-secondary meter-sr">{{ $i + 1 }}</span>
                                    <button type="button" class="btn btn-sm btn-danger py-0 px-2 remove-meter">X</button>
                                </div>
                                <label class="form-label small mb-1">Meter</label>
                                <input type="number" name="meters[{{ $i }}]" value="{{ $meter }}" step="0.01" min="0.01" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Delivery Challan' : 'Create Delivery Challan' }}</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const customerSelect = document.getElementById('customer_id');
    const receiverName = document.getElementById('receiver_name');
    const receiverAddress = document.getElementById('receiver_address');
    const receiverGstin = document.getElementById('receiver_gstin');
    const consigneeName = document.getElementById('consignee_name');
    const consigneeAddress = document.getElementById('consignee_address');
    const consigneeGstin = document.getElementById('consignee_gstin');

    customerSelect.addEventListener('change', () => {
        const option = customerSelect.options[customerSelect.selectedIndex];
        if (!option.value) return;

        if (!receiverName.value) receiverName.value = option.dataset.name || '';
        if (!receiverAddress.value) receiverAddress.value = option.dataset.address || '';
        if (!receiverGstin.value) receiverGstin.value = option.dataset.gstin || '';

        if (!consigneeName.value) consigneeName.value = option.dataset.name || '';
        if (!consigneeAddress.value) consigneeAddress.value = option.dataset.address || '';
        if (!consigneeGstin.value) consigneeGstin.value = option.dataset.gstin || '';
    });

    const meterGrid = document.getElementById('meterGrid');
    const addMeterBtn = document.getElementById('addMeterBtn');

    function reindexMeters() {
        [...meterGrid.querySelectorAll('.meter-entry')].forEach((entry, index) => {
            entry.querySelector('.meter-sr').textContent = index + 1;
            entry.querySelector('input').name = `meters[${index}]`;
        });
    }

    addMeterBtn.addEventListener('click', () => {
        const index = meterGrid.querySelectorAll('.meter-entry').length;
        const col = document.createElement('div');
        col.className = 'col-md-3 meter-entry';
        col.innerHTML = `
            <div class="border rounded p-2 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge text-bg-secondary meter-sr">${index + 1}</span>
                    <button type="button" class="btn btn-sm btn-danger py-0 px-2 remove-meter">X</button>
                </div>
                <label class="form-label small mb-1">Meter</label>
                <input type="number" name="meters[${index}]" step="0.01" min="0.01" value="0" class="form-control form-control-sm" required>
            </div>
        `;
        meterGrid.appendChild(col);
    });

    meterGrid.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-meter')) return;
        if (meterGrid.querySelectorAll('.meter-entry').length <= 1) return;
        e.target.closest('.meter-entry').remove();
        reindexMeters();
    });
</script>
@endsection
