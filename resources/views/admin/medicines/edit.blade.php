@extends('admin.layouts.app')

@section('title', 'Edit Medicine')
@section('page-title', 'Edit Medicine')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-pills me-2"></i>Edit Medicine</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Edit form --}}
                    <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Category --}}
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ (old('category_id') ?? $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Medicine Name --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Medicine Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name') ?? $medicine->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Brand --}}
                        <div class="form-group mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                id="brand" name="brand" 
                                value="{{ old('brand') ?? $medicine->brand }}">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Form --}}
                        <div class="form-group mb-3">
                            <label for="form" class="form-label">Form</label>
                            <input type="text" class="form-control @error('form') is-invalid @enderror" 
                                id="form" name="form" 
                                value="{{ old('form') ?? $medicine->form }}" 
                                placeholder="e.g., tablet, capsule, injection">
                            @error('form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Strength --}}
                        <div class="form-group mb-3">
    <label for="form" class="form-label">Form</label>
    <select class="form-control @error('form') is-invalid @enderror" 
            id="form" name="form">
        <option value="">-- Select Form --</option>
        <option value="tablet" {{ (old('form') ?? $medicine->form) == 'tablet' ? 'selected' : '' }}>Tablet</option>
        <option value="capsule" {{ (old('form') ?? $medicine->form) == 'capsule' ? 'selected' : '' }}>Capsule</option>
        <option value="syrup" {{ (old('form') ?? $medicine->form) == 'syrup' ? 'selected' : '' }}>Syrup</option>
        <option value="injection" {{ (old('form') ?? $medicine->form) == 'injection' ? 'selected' : '' }}>Injection</option>
        <option value="cream" {{ (old('form') ?? $medicine->form) == 'cream' ? 'selected' : '' }}>Cream</option>
        <option value="drops" {{ (old('form') ?? $medicine->form) == 'drops' ? 'selected' : '' }}>Drops</option>
        <option value="other" {{ (old('form') ?? $medicine->form) == 'other' ? 'selected' : '' }}>Other</option>
    </select>
    @error('form')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        {{-- Condition Notes --}}
                        <div class="form-group mb-4">
                            <label for="condition_notes" class="form-label">Condition Notes</label>
                            <textarea class="form-control @error('condition_notes') is-invalid @enderror" 
                                id="condition_notes" name="condition_notes" rows="3">{{ old('condition_notes') ?? $medicine->condition_notes }}</textarea>
                            @error('condition_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Medicines
                            </a>
                            <div>
                                <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i> View
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
