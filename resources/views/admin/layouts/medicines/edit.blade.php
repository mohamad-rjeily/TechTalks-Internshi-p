@extends('admin.layouts.app')

@section('title', 'Edit Medicine')
@section('page-title', 'Edit Medicine')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Medicine</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Medicine Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $medicine->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                   id="brand" name="brand" value="{{ old('brand', $medicine->brand) }}">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="form">Form</label>
                            <input type="text" class="form-control @error('form') is-invalid @enderror" 
                                   id="form" name="form" value="{{ old('form', $medicine->form) }}" placeholder="e.g., tablet, capsule, injection">
                            @error('form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="strength">Strength</label>
                            <input type="text" class="form-control @error('strength') is-invalid @enderror" 
                                   id="strength" name="strength" value="{{ old('strength', $medicine->strength) }}" placeholder="e.g., 500mg, 10ml">
                            @error('strength')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="condition_notes">Condition Notes</label>
                            <textarea class="form-control @error('condition_notes') is-invalid @enderror" 
                                      id="condition_notes" name="condition_notes" rows="3">{{ old('condition_notes', $medicine->condition_notes) }}</textarea>
                            @error('condition_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Medicine
                            </button>
                            <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="btn btn-secondary">
                                <i class="fas fa-eye me-2"></i>View Medicine
                            </a>
                            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Medicines
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
