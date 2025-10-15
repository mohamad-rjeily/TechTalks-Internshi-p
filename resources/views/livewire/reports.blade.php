<div>
    {{-- Page Header --}}
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-success">
                <i class="bi bi-flag-fill me-2"></i> Reports
            </h5>
            {{-- Create Report Button --}}
            <div class="text-end mb-3">
                <button class="btn btn-success shadow-sm rounded-pill" wire:click="$set('showCreateModal', true)">
                    <i class="bi bi-plus-circle me-1"></i> Create Report
                </button>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if ($flashVisible && $flashMessage)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.hideFlash(); }, 5000)"
             x-show="show"
             x-transition
             class="alert alert-{{ $flashType }} border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
            <i class="bi {{ $flashType === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger' }} me-2"></i>
            <div class="flex-grow-1 fw-semibold">{{ $flashMessage }}</div>
            <button type="button" class="btn-close" @click="show = false; $wire.hideFlash();"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card border-0">
            <h6 class="mb-0 fw-semibold text-success">
                <i class="bi bi-funnel-fill me-2"></i> Filter Reports
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <select class="form-select shadow-sm" wire:model.live="targetTypeFilter">
                        <option value="">All Types</option>
                        <option value="user">User</option>
                        <option value="medicine">Medicine</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select shadow-sm" wire:model.live="statusFilter">
                        <option value="all">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">From Date</label>
                    <input type="date" class="form-control shadow-sm" wire:model.live="fromDate">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">To Date</label>
                    <input type="date" class="form-control shadow-sm" wire:model.live="toDate">
                </div>
            </div>
        </div>
    </div>

    {{-- Reports Table --}}
    @if ($reports->count() > 0)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold text-success">
                    <i class="bi bi-flag-fill me-2"></i> My Reports ({{ $reports->total() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Target</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr wire:key="report-{{ $report->id }}">
                                    <td>{{ $report->target?->name ?? '—' }}</td>
                                    <td>{{ ucfirst($report->target_type) }}</td>
                                    <td>{{ Str::limit($report->reason, 40) }}</td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 shadow-sm
                                            {{ $report->status === 'open' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }}">
                                            <i class="bi {{ $report->status === 'open' ? 'bi-hourglass-split' : 'bi-check-circle-fill' }} me-1"></i>
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary shadow-sm me-1"
                                                wire:click="startView({{ $report->id }})">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @else
        <div class="card text-center py-5 border-0 bg-light shadow-sm rounded-3">
            <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
            <h6 class="mt-2 text-muted">No reports found</h6>
        </div>
    @endif

    {{-- Modals --}}
    @include('livewire.partials.create-report-modal')
    @include('livewire.partials.view-report-modal')
</div>
