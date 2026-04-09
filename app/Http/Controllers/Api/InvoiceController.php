<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $perPage = (int) request()->integer('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $filters = request()->only([
            'status',
            'customer_id',
            'invoice_number',
            'from_date',
            'to_date',
        ]);

        $invoices = $this->invoiceService->paginate($filters, $perPage);

        return InvoiceResource::collection($invoices);
    }

    public function store(StoreInvoiceRequest $request): InvoiceResource
    {
        $invoice = $this->invoiceService->create($request->validated());

        return new InvoiceResource($invoice);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        return new InvoiceResource($invoice->load(['customer', 'items']));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): InvoiceResource
    {
        $updatedInvoice = $this->invoiceService->update($invoice->load('items'), $request->validated());

        return new InvoiceResource($updatedInvoice);
    }

    public function destroy(Invoice $invoice): Response
    {
        $this->invoiceService->delete($invoice->load('items'));

        return response()->noContent();
    }
}
