<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveryChallanRequest;
use App\Http\Requests\UpdateDeliveryChallanRequest;
use App\Models\Customer;
use App\Models\DeliveryChallan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DeliveryChallanController extends Controller
{
    public function index(): View
    {
        $challans = DeliveryChallan::query()
            ->with('customer')
            ->latest('id')
            ->paginate(15);

        return view('delivery_challans.index', compact('challans'));
    }

    public function create(): View
    {
        $customers = Customer::query()->orderBy('name')->get();

        return view('delivery_challans.create', compact('customers'));
    }

    public function store(StoreDeliveryChallanRequest $request): RedirectResponse
    {
        $challan = DB::transaction(function () use ($request): DeliveryChallan {
            $data = $request->validated();
            $challan = DeliveryChallan::query()->create([
                ...$data,
                'challan_number' => $this->nextChallanNumber(),
            ]);

            $this->syncMeters($challan, $data['meters']);

            return $challan;
        });

        return redirect()->route('delivery-challans.show', $challan)->with('success', 'Delivery challan created successfully.');
    }

    public function show(DeliveryChallan $deliveryChallan): View
    {
        $deliveryChallan->load(['customer', 'meters']);

        return view('delivery_challans.show', compact('deliveryChallan'));
    }

    public function edit(DeliveryChallan $deliveryChallan): View
    {
        $deliveryChallan->load(['customer', 'meters']);
        $customers = Customer::query()->orderBy('name')->get();

        return view('delivery_challans.create', compact('deliveryChallan', 'customers'));
    }

    public function update(UpdateDeliveryChallanRequest $request, DeliveryChallan $deliveryChallan): RedirectResponse
    {
        DB::transaction(function () use ($request, $deliveryChallan): void {
            $data = $request->validated();

            $deliveryChallan->update($data);
            $deliveryChallan->meters()->forceDelete();
            $this->syncMeters($deliveryChallan, $data['meters']);
        });

        return redirect()->route('delivery-challans.show', $deliveryChallan)->with('success', 'Delivery challan updated successfully.');
    }

    public function destroy(DeliveryChallan $deliveryChallan): RedirectResponse
    {
        DB::transaction(function () use ($deliveryChallan): void {
            $deliveryChallan->meters()->forceDelete();
            $deliveryChallan->delete();
        });

        return redirect()->route('delivery-challans.index')->with('success', 'Delivery challan deleted successfully.');
    }

    public function preview(DeliveryChallan $deliveryChallan): View
    {
        $deliveryChallan->load(['customer', 'meters']);

        return view('delivery_challans.pdf', compact('deliveryChallan'));
    }

    public function download(DeliveryChallan $deliveryChallan): Response
    {
        $deliveryChallan->load(['customer', 'meters']);

        $pdf = Pdf::loadView('delivery_challans.pdf', compact('deliveryChallan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('delivery-challan-' . $deliveryChallan->challan_number . '.pdf');
    }

    private function nextChallanNumber(): string
    {
        $last = DeliveryChallan::withTrashed()->latest('id')->value('challan_number');

        if ($last === null) {
            return 'DC-0001';
        }

        $number = (int) substr($last, 3);

        return 'DC-' . str_pad((string) ($number + 1), 4, '0', STR_PAD_LEFT);
    }

    private function syncMeters(DeliveryChallan $challan, array $meters): void
    {
        $rows = [];
        $sr = 1;

        foreach ($meters as $meter) {
            if ($meter === null || $meter === '') {
                continue;
            }

            $rows[] = [
                'sr_no' => $sr++,
                'meter' => $meter,
            ];
        }

        $challan->meters()->createMany($rows);
    }
}
