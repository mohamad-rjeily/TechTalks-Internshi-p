@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Edit Donation #{{ $donation->id }}</h1>

    <form method="POST" action="{{ route('donations.update', $donation) }}" class="row g-3 ms-appear ms-form p-3 rounded bg-white mt-3">
        @csrf
        @method('PUT')

        <div class="col-md-6">
            <label class="form-label">Medicine</label>
            <input type="number" name="medicine_id" class="form-control" value="{{ $donation->medicine_id }}" readonly>
        </div>

        <div class="col-md-4">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $donation->quantity }}">
        </div>
        <!-- Donor/Recipient/Status are not editable here to match Create -->
        <div class="col-md-4">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $donation->expiry_date ? \Illuminate\Support\Carbon::parse($donation->expiry_date)->format('Y-m-d') : '') }}">
        </div>

        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ $donation->notes }}</textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection


