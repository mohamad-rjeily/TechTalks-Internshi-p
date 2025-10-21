@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Create Report Header -->
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-semibold text-primary">
                <i class="bi bi-plus-circle me-2"></i> Create New Report
            </h5>
        </div>
    </div>

    <!-- Create Report Form -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('reports.store') }}" method="POST">
                @csrf

                <!-- Reported User -->
                <div class="mb-4">
                    <label for="reported_id" class="form-label fw-semibold">
                        Reported User <span class="text-danger">*</span>
                    </label>
                    <select name="reported_id" id="reported_id" 
                            class="form-select @error('reported_id') is-invalid @enderror" 
                            required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('reported_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('reported_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Select the user being reported</small>
                </div>

                <!-- Target ID -->
                <div class="mb-4">
                    <label for="target_id" class="form-label fw-semibold">
                        Target ID <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="target_id" 
                           id="target_id" 
                           class="form-control @error('target_id') is-invalid @enderror" 
                           value="{{ old('target_id') }}" 
                           placeholder="Enter target ID (e.g., post ID, comment ID)" 
                           required>
                    @error('target_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Enter the ID of the content being reported</small>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label for="reason" class="form-label fw-semibold">
                        Reason <span class="text-danger">*</span>
                    </label>
                    <textarea name="reason" 
                              id="reason" 
                              rows="5" 
                              class="form-control @error('reason') is-invalid @enderror" 
                              placeholder="Describe the reason for this report..." 
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Provide a detailed explanation of the issue</small>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="form-select @error('status') is-invalid @enderror" 
                            required>
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin Notes (Optional) -->
                <div class="mb-4">
                    <label for="admin_notes" class="form-label fw-semibold">
                        Admin Notes <span class="text-muted">(Optional)</span>
                    </label>
                    <textarea name="admin_notes" 
                              id="admin_notes" 
                              rows="3" 
                              class="form-control @error('admin_notes') is-invalid @enderror" 
                              placeholder="Add any admin notes...">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Internal notes for administrators</small>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Create Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection