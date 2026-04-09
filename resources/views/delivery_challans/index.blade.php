@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Delivery Challans</h4>
        <a href="{{ route('delivery-challans.create') }}" class="btn btn-primary">Create Delivery Challan</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Challan No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Quality</th>
                    <th>Broker</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($challans as $challan)
                    <tr>
                        <td>{{ $challan->challan_number }}</td>
                        <td>{{ $challan->challan_date?->format('Y-m-d') }}</td>
                        <td>{{ $challan->customer?->name }}</td>
                        <td>{{ $challan->quality ?: '-' }}</td>
                        <td>{{ $challan->broker ?: '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('delivery-challans.show', $challan) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('delivery-challans.edit', $challan) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('delivery-challans.destroy', $challan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this challan?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">No delivery challans found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $challans->links() }}
        </div>
    </div>
@endsection
