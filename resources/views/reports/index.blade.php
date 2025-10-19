@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Reports Header -->
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-primary">
                <i class="bi bi-flag me-2"></i> Reports
            </h5>
            <a href="{{ route('reports.create') }}" class="btn btn-primary shadow-sm rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Create Report
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <div class="fw-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <div class="fw-semibold">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Reports Table -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Reporter</th>
                            <th>Target ID</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->reporter?->name ?? '—' }}</td>
                                <td>{{ $report->target_id ?? '—' }}</td>
                                <td>{{ Str::limit($report->reason, 50) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($report->status) {
                                            'open'     => 'bg-warning-subtle text-warning',
                                            'resolved' => 'bg-success-subtle text-success',
                                            default    => 'bg-secondary-subtle text-secondary',
                                        };
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                        {{ ucfirst($report->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>{{ $report->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('reports.show', $report->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('reports.edit', $report->id) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('reports.destroy', $report->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this report?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                    <div class="mt-2">No reports found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection