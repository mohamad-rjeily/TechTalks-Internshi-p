@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Browse Medicines</h1>
        <a class="btn btn-outline-secondary" href="{{ url('/') }}">Home</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @forelse($medicines as $medicine)
            <div class="col-12 col-sm-6 col-lg-4 ms-appear">
                <div class="card h-100 ms-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $medicine->name }}</h5>
                        <p class="text-muted mb-2">Category: {{ optional($medicine->category)->name ?? 'Uncategorized' }}</p>
                        <p class="mb-2">Brand: {{ $medicine->brand ?? '—' }}</p>
                        <p class="mb-2">Form: {{ $medicine->form ?? '—' }}</p>
                        <p class="mb-3">Strength: {{ $medicine->strength ?? '—' }}</p>

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('requests.create', ['medicine_id' => $medicine->id]) }}" class="btn btn-primary btn-sm">Request</a>
                            <a href="{{ route('donations.create', ['medicine_id' => $medicine->id]) }}" class="btn btn-outline-success btn-sm">Donate</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No medicines available yet.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection


