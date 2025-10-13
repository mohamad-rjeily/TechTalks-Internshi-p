@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Browse Medicines</h1>
        <a class="btn btn-outline-secondary" href="{{ url('/') }}">Home</a>
    </div>

    {{-- Mount the two modal components (no tables here) --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition
             class="alert alert-success alert-dismissible fade show mt-2">
            {{ session('success') }}
            <button type="button" class="btn-close" aria-label="Close" @click="show = false"></button>
        </div>
    @endif
    <livewire:request-create-modal />
    <livewire:donation-portal />

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @forelse($medicines as $medicine)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $medicine->name }}</h5>
                        <p class="text-muted mb-2">Category: {{ optional($medicine->category)->name ?? 'Uncategorized' }}</p>
                        <p class="mb-2">Brand: {{ $medicine->brand ?? '—' }}</p>
                        <p class="mb-2">Form: {{ $medicine->form ?? '—' }}</p>
                        <p class="mb-3">Strength: {{ $medicine->strength ?? '—' }}</p>

                        <div class="mt-auto d-flex gap-2">
                            <button class="btn btn-primary btn-sm"
                                    onclick="Livewire.dispatch('openRequestFromBrowse', [{{ $medicine->id }}])">
                                Request
                            </button>
                            <button class="btn btn-outline-success btn-sm"
                                    onclick="Livewire.dispatch('openDonationFromBrowse', [{{ $medicine->id }}])">
                                Donate
                            </button>
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
