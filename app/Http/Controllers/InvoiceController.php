<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService)
    {
    }

    public function index(): View
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
        $customers = Customer::query()->orderBy('name')->get(['id', 'name']);

        return view('invoices.index', compact('invoices', 'customers', 'filters'));
    }

    public function create(): View
    {
        $customers = Customer::query()->orderBy('name')->get(['id', 'name']);

        return view('invoices.create', compact('customers'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = $this->invoiceService->create($request->validated());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice = $invoice->load(['customer', 'items']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice = $invoice->load('items');
        $customers = Customer::query()->orderBy('name')->get(['id', 'name']);

        return view('invoices.create', compact('invoice', 'customers'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $updatedInvoice = $this->invoiceService->update($invoice->load('items'), $request->validated());

        return redirect()->route('invoices.show', $updatedInvoice)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->delete($invoice->load('items'));

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function downloadPdf(Invoice $invoice): Response
    {
        $invoice = $invoice->load(['customer', 'items']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        $fileName = sprintf('invoice-%s.pdf', $invoice->invoice_number);

        return $pdf->download($fileName);
    }

    public function previewPdf(Invoice $invoice): View
    {
        $invoice = $invoice->load(['customer', 'items']);

        return view('invoices.pdf', compact('invoice'));
    }
}
