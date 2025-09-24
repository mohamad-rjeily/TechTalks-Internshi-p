@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">My Donations</h1>
        <a href="{{ route('donations.create') }}" class="btn btn-success">New Donation</a>
    </div>


    <div class="table-responsive ms-appear">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Expiry</th>
                    <th>Recipient</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                    <tr class="ms-appear">
                        <td>{{ $donation->id }}</td>
                        <td>{{ optional($donation->medicine)->name }}</td>
                        <td>{{ $donation->quantity }}</td>
                        <td>
                            @php
                                $computedStatus = ($donation->expiry_date && \Illuminate\Support\Carbon::parse($donation->expiry_date)->isPast())
                                    ? 'expired'
                                    : ($donation->status ?? 'unknown');
                                $badgeClass = match($computedStatus) {
                                    'available' => 'text-bg-success',
                                    'unavailable' => 'text-bg-danger',
                                    'expired' => 'text-bg-warning',
                                    default => 'text-bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $computedStatus }}</span>
                        </td>
                        <td>{{ $donation->expiry_date }}</td>
                        <td>{{ optional($donation->recipient)->name ?? '—' }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('donations.destroy', $donation) }}" onsubmit="return confirm('Delete this donation?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">You have no donations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


