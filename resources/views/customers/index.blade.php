@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Customers</h4>
        <div class="app-page-actions">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
    </div>

    <div class="card shadow-sm desktop-table-wrap d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>GST</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->gst_number ?: '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">No customers found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $customers->links() }}
        </div>
    </div>

    <div class="mobile-card-wrap app-mobile-cards d-grid gap-2 d-lg-none">
        @forelse($customers as $customer)
            <div class="app-mobile-card">
                <div class="title">{{ $customer->name }}</div>
                <div class="meta mb-1">{{ $customer->email }} | {{ $customer->phone }}</div>
                <div class="meta mb-2">GST: {{ $customer->gst_number ?: '-' }}</div>
                <div class="app-mobile-actions">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Delete this customer?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="app-mobile-card text-center">No customers found.</div>
        @endforelse
        <div class="card shadow-sm">
            <div class="card-body">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
