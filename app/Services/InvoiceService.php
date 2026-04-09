<?php

namespace App\Services;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(private readonly InvoiceRepositoryInterface $invoiceRepository)
    {
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->invoiceRepository->paginate($filters, $perPage);
    }

    public function findOrFail(int $id): Invoice
    {
        return $this->invoiceRepository->findOrFail($id);
    }

    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data): Invoice {
            $totals = $this->calculateTotals($data['items'], (float) $data['tax_percentage']);

            $invoice = $this->invoiceRepository->create([
                'invoice_number' => $this->invoiceRepository->nextInvoiceNumber(),
                'customer_id' => $data['customer_id'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'subtotal' => $totals['subtotal'],
                'tax_percentage' => $data['tax_percentage'],
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'status' => $data['status'] ?? 'unpaid',
                'notes' => $data['notes'] ?? null,
            ]);

            $invoice->items()->createMany($totals['items']);

            return $invoice->refresh()->load(['customer', 'items']);
        });
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data): Invoice {
            $items = $data['items'] ?? $invoice->items->map(fn ($item) => [
                'item_name' => $item->item_name,
                'quantity' => (float) $item->quantity,
                'price' => (float) $item->price,
            ])->toArray();

            $taxPercentage = (float) ($data['tax_percentage'] ?? $invoice->tax_percentage);
            $totals = $this->calculateTotals($items, $taxPercentage);

            $updatedInvoice = $this->invoiceRepository->update($invoice, [
                'customer_id' => $data['customer_id'] ?? $invoice->customer_id,
                'invoice_date' => $data['invoice_date'] ?? $invoice->invoice_date->toDateString(),
                'due_date' => $data['due_date'] ?? $invoice->due_date->toDateString(),
                'subtotal' => $totals['subtotal'],
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $totals['tax_amount'],
                'total_amount' => $totals['total_amount'],
                'status' => $data['status'] ?? $invoice->status,
                'notes' => $data['notes'] ?? $invoice->notes,
            ]);

            if (array_key_exists('items', $data)) {
                $updatedInvoice->items()->delete();
                $updatedInvoice->items()->createMany($totals['items']);
            }

            return $updatedInvoice->refresh()->load(['customer', 'items']);
        });
    }

    public function delete(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice): void {
            $invoice->items()->delete();
            $this->invoiceRepository->delete($invoice);
        });
    }

    private function calculateTotals(array $items, float $taxPercentage): array
    {
        $preparedItems = [];
        $subtotal = 0.0;

        foreach ($items as $item) {
            $lineTotal = round(((float) $item['quantity']) * ((float) $item['price']), 2);

            $preparedItems[] = [
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
        }

        $subtotal = round($subtotal, 2);
        $taxAmount = round(($subtotal * $taxPercentage) / 100, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        return [
            'items' => $preparedItems,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }
}
