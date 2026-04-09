<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository)
    {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->customerRepository->paginate($perPage);
    }

    public function findOrFail(int $id): Customer
    {
        return $this->customerRepository->findOrFail($id);
    }

    public function create(array $data): Customer
    {
        return $this->customerRepository->create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        return $this->customerRepository->update($customer, $data);
    }

    public function delete(Customer $customer): void
    {
        $this->customerRepository->delete($customer);
    }
}
