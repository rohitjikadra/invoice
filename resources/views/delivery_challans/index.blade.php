@extends('layouts.app')

@section('content')
    <div class="app-page-header">
        <h4 class="mb-0">Delivery Challans</h4>
        <div class="app-page-actions">
            <a href="{{ route('delivery-challans.create') }}" class="btn btn-primary">Create Delivery Challan</a>
        </div>
    </div>

    <div class="card shadow-sm desktop-table-wrap d-none d-lg-block">
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
                    @php
                        $challanDisplayNumber = (int) ltrim((string) preg_replace('/[^0-9]/', '', (string) $challan->challan_number), '0');
                        if ($challanDisplayNumber === 0) {
                            $challanDisplayNumber = (int) $challan->id;
                        }
                    @endphp
                    <tr>
                        <td>{{ $challanDisplayNumber }}</td>
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

    <div class="mobile-card-wrap app-mobile-cards d-grid gap-2 d-lg-none">
        @forelse($challans as $challan)
            @php
                $challanDisplayNumber = (int) ltrim((string) preg_replace('/[^0-9]/', '', (string) $challan->challan_number), '0');
                if ($challanDisplayNumber === 0) {
                    $challanDisplayNumber = (int) $challan->id;
                }
            @endphp
            <div class="app-mobile-card">
                <div class="title">{{ $challanDisplayNumber }} - {{ $challan->customer?->name }}</div>
                <div class="meta mb-1">Date: {{ $challan->challan_date?->format('Y-m-d') }}</div>
                <div class="meta mb-2">Quality: {{ $challan->quality ?: '-' }} | Broker: {{ $challan->broker ?: '-' }}</div>
                <div class="app-mobile-actions">
                    <a href="{{ route('delivery-challans.show', $challan) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('delivery-challans.edit', $challan) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('delivery-challans.destroy', $challan) }}" method="POST" class="flex-grow-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Delete this challan?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="app-mobile-card text-center">No delivery challans found.</div>
        @endforelse
        <div class="card shadow-sm">
            <div class="card-body">
                {{ $challans->links() }}
            </div>
        </div>
    </div>
@endsection
