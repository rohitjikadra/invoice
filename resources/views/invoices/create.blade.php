@extends('layouts.app')

@php
    $isEdit = isset($invoice);
@endphp

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">{{ $isEdit ? 'Edit Invoice' : 'Create Invoice' }}</h4>
        <div class="app-page-actions">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ $isEdit ? route('invoices.update', $invoice) : route('invoices.store') }}">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @selected((string) old('customer_id', $invoice->customer_id ?? '') === (string) $customer->id)>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', isset($invoice) ? $invoice->invoice_date?->toDateString() : now()->toDateString()) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', isset($invoice) ? $invoice->due_date?->toDateString() : now()->addDays(7)->toDateString()) }}" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tax %</label>
                        <input type="number" name="tax_percentage" id="tax_percentage" step="0.01" min="0" max="100" value="{{ old('tax_percentage', $invoice->tax_percentage ?? 0) }}" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['paid', 'unpaid', 'overdue'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $invoice->status ?? 'unpaid') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes ?? '') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Items</h5>
                    <button type="button" class="btn btn-sm btn-dark" id="addItemBtn">+ Add Item</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th style="width: 130px;">Qty</th>
                            <th style="width: 160px;">Price</th>
                            <th style="width: 160px;">Total</th>
                            <th style="width: 80px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $oldItems = old('items');
                            $existingItems = isset($invoice) ? $invoice->items->map(fn($i) => ['item_name' => $i->item_name, 'quantity' => $i->quantity, 'price' => $i->price])->toArray() : [];
                            $items = $oldItems ?? (count($existingItems) ? $existingItems : [['item_name' => '', 'quantity' => 1, 'price' => 0]]);
                        @endphp
                        @foreach($items as $i => $item)
                            <tr>
                                <td><input type="text" name="items[{{ $i }}][item_name]" value="{{ $item['item_name'] }}" class="form-control" required></td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" value="{{ $item['quantity'] }}" step="0.01" min="0.01" class="form-control qty" required></td>
                                <td><input type="number" name="items[{{ $i }}][price]" value="{{ $item['price'] }}" step="0.01" min="0" class="form-control price" required></td>
                                <td><input type="text" class="form-control row-total" readonly></td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-item">X</button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr><th>Subtotal</th><td class="text-end" id="subtotalText">0.00</td></tr>
                            <tr><th>Tax Amount</th><td class="text-end" id="taxText">0.00</td></tr>
                            <tr><th>Total</th><td class="text-end fw-bold" id="totalText">0.00</td></tr>
                        </table>
                    </div>
                </div>

                <div class="app-submit-bar">
                    <button type="submit" class="btn btn-primary w-100 w-lg-auto">{{ $isEdit ? 'Update Invoice' : 'Create Invoice' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const tbody = document.querySelector('#itemsTable tbody');
    const addItemBtn = document.getElementById('addItemBtn');
    const taxInput = document.getElementById('tax_percentage');

    function reindexRows() {
        [...tbody.querySelectorAll('tr')].forEach((row, index) => {
            row.querySelector('input[name*="[item_name]"]').name = `items[${index}][item_name]`;
            row.querySelector('input[name*="[quantity]"]').name = `items[${index}][quantity]`;
            row.querySelector('input[name*="[price]"]').name = `items[${index}][price]`;
        });
    }

    function recalculate() {
        let subtotal = 0;

        [...tbody.querySelectorAll('tr')].forEach((row) => {
            const qty = parseFloat(row.querySelector('.qty').value || 0);
            const price = parseFloat(row.querySelector('.price').value || 0);
            const total = qty * price;
            row.querySelector('.row-total').value = total.toFixed(2);
            subtotal += total;
        });

        const taxPercentage = parseFloat(taxInput.value || 0);
        const taxAmount = subtotal * taxPercentage / 100;
        const grandTotal = subtotal + taxAmount;

        document.getElementById('subtotalText').textContent = subtotal.toFixed(2);
        document.getElementById('taxText').textContent = taxAmount.toFixed(2);
        document.getElementById('totalText').textContent = grandTotal.toFixed(2);
    }

    addItemBtn.addEventListener('click', () => {
        const index = tbody.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="items[${index}][item_name]" class="form-control" required></td>
            <td><input type="number" name="items[${index}][quantity]" step="0.01" min="0.01" value="1" class="form-control qty" required></td>
            <td><input type="number" name="items[${index}][price]" step="0.01" min="0" value="0" class="form-control price" required></td>
            <td><input type="text" class="form-control row-total" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-item">X</button></td>
        `;
        tbody.appendChild(tr);
        recalculate();
    });

    tbody.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            if (tbody.querySelectorAll('tr').length <= 1) return;
            e.target.closest('tr').remove();
            reindexRows();
            recalculate();
        }
    });

    tbody.addEventListener('input', (e) => {
        if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
            recalculate();
        }
    });

    taxInput.addEventListener('input', recalculate);
    recalculate();
</script>
@endsection
