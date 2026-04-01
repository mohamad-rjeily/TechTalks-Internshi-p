@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Create Donation</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('donations.store') }}" class="row g-3 ms-appear ms-form p-3 rounded bg-white mt-3">
        @csrf

        <div class="col-md-6">
            <label class="form-label">Medicine</label>
            <select name="medicine_id" class="form-select" required>
                <option value="">Select medicine</option>
                @foreach($medicines as $m)
                    <option value="{{ $m->id }}" @selected(old('medicine_id', request('medicine_id'))==$m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <!-- Donor, Recipient and Status are set automatically on submit -->
        <div class="col-md-4">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


