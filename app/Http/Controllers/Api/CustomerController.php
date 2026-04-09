<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customerService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $perPage = (int) request()->integer('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $customers = $this->customerService->paginate($perPage);

        return CustomerResource::collection($customers);
    }

    public function store(StoreCustomerRequest $request): CustomerResource
    {
        $customer = $this->customerService->create($request->validated());

        return new CustomerResource($customer);
    }

    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $updatedCustomer = $this->customerService->update($customer, $request->validated());

        return new CustomerResource($updatedCustomer);
    }

    public function destroy(Customer $customer): Response
    {
        $this->customerService->delete($customer);

        return response()->noContent();
    }
}
