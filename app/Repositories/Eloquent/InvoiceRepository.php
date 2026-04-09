<?php

namespace App\Repositories\Eloquent;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Invoice::query()
            ->with(['customer', 'items'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['customer_id'] ?? null, fn ($query, $customerId) => $query->where('customer_id', $customerId))
            ->when($filters['invoice_number'] ?? null, fn ($query, $invoiceNumber) => $query->where('invoice_number', 'like', '%' . $invoiceNumber . '%'))
            ->when($filters['from_date'] ?? null, fn ($query, $fromDate) => $query->whereDate('invoice_date', '>=', $fromDate))
            ->when($filters['to_date'] ?? null, fn ($query, $toDate) => $query->whereDate('invoice_date', '<=', $toDate))
            ->latest()
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Invoice
    {
        return Invoice::query()->with(['customer', 'items'])->findOrFail($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::query()->create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);

        return $invoice->refresh()->load(['customer', 'items']);
    }

    public function delete(Invoice $invoice): void
    {
        $invoice->delete();
    }

    public function nextInvoiceNumber(): string
    {
        $lastInvoiceNumber = Invoice::withTrashed()->latest('id')->value('invoice_number');

        if ($lastInvoiceNumber === null) {
            return 'INV-0001';
        }

        $numericPart = (int) substr($lastInvoiceNumber, 4);
        $next = $numericPart + 1;

        return 'INV-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
