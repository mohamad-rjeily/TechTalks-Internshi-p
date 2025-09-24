@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Create Request</h1>

    <form method="POST" action="{{ route('requests.store') }}" class="row g-3 ms-appear ms-form p-3 rounded bg-white mt-3">
        @csrf

        <div class="col-md-6">
            <label class="form-label">Medicine</label>
            <select name="medicine_id" class="form-select" required>
                <option value="">Select available medicine</option>
                @foreach($medicines as $m)
                    <option value="{{ $m->id }}" @selected(old('medicine_id', request('medicine_id'))==$m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Quantity Needed</label>
            <input type="number" name="quantity_requested" class="form-control" min="1" value="1" required>
        </div>

        <div class="col-12">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="3"></textarea>
        </div>

        <!-- Status and requester are auto-set in controller -->

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('requests.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


