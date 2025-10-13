<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
</div>
<div>
    {{-- Flash Message --}}
    @if ($flashVisible && $flashMessage)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.hideFlash(); }, 5000)"
             x-show="show"
             x-transition
             class="alert alert-{{ $flashType }} border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
            <i class="bi {{ $flashType === 'success' 
                ? 'bi-check-circle-fill text-success' 
                : 'bi-exclamation-triangle-fill text-danger' }} me-2"></i>
            <div class="flex-grow-1 fw-semibold">{{ $flashMessage }}</div>
            <button type="button" class="btn-close" @click="show = false; $wire.hideFlash();"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm border-0 rounded-3 mb-3 mt-3">
        <div class="card border-0">
            <h6 class="mb-0 fw-semibold text-success">
                <i class="bi bi-funnel-fill me-2"></i> Filter Requests
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <select class="form-select shadow-sm" wire:model.live="medicineFilter">
                        <option value="">All Medicines</option>
                        @foreach ($medicines as $med)
                            <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->category->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select shadow-sm" wire:model.live="statusFilter">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fromDate" class="form-label text-muted small mb-1">From Date</label>
                    <input type="date" id="fromDate" class="form-control shadow-sm" wire:model.live="fromDate">
                </div>
                <div class="col-md-2">
                    <label for="toDate" class="form-label text-muted small mb-1">To Date</label>
                    <input type="date" id="toDate" class="form-control shadow-sm" wire:model.live="toDate">
                </div>
            </div>
        </div>
    </div>
    
    {{-- Requests Table --}}
    @if ($sentRequests->count() > 0)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold text-success">
                    <i class="bi bi-list-check me-2"></i>My Requests ({{ $sentRequests->total() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th class="text-center">Image</th>
                                <th class="text-center">Medicine</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Donor</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sentRequests as $req)
                                <tr wire:key="req-{{ $req->id }}">
                                    <td class="text-center">
                                        @if (!empty($req->medicine?->photo_path))
                                            <img src="{{ asset('storage/' . $req->medicine->photo_path) }}"
                                                 alt="Medicine" width="48" height="48"
                                                 class="shadow-sm">
                                        @else
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                style="width:56px; height:56px;">
                                                <i class="bi bi-capsule text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $req->medicine?->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $req->medicine?->category->name ?? '' }}</div>
                                    </td>
                                    <td class="text-center fw-bold text-success">{{ $req->quantity_remaining }}</td>
                                    <td class="text-center fw-semibold">{{ $req->donor?->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 shadow-sm
                                            @switch($req->status)
                                                @case('pending') bg-warning-subtle text-warning @break
                                                @case('approved') bg-info-subtle text-info @break
                                                @case('completed') bg-success-subtle text-success @break
                                                @case('rejected') bg-danger text-white @break
                                                @case('cancelled') bg-danger-subtle text-danger @break
                                                @default bg-secondary-subtle
                                            @endswitch">

                                            @switch($req->status)
                                                @case('pending') <i class="bi bi-hourglass-split me-1"></i> @break
                                                @case('approved') <i class="bi bi-check-circle me-1"></i> @break
                                                @case('completed') <i class="bi bi-check-circle-fill me-1"></i> @break
                                                @case('rejected') <i class="bi bi-x-circle me-1"></i> @break
                                                @case('cancelled') <i class="bi bi-x-circle me-1"></i> @break
                                                @default <i class="bi bi-question-circle me-1"></i>
                                            @endswitch
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $req->created_at?->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        @if ($req->status === 'pending')
                                            <button class="btn btn-sm btn-outline-primary shadow-sm me-1"
                                                    wire:click="startEdit({{ $req->id }})">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger shadow-sm"
                                                    wire:click="cancelRequest({{ $req->id }})">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                        @elseif ($req->status === 'approved')
                                            <button class="btn btn-sm btn-success shadow-sm"
                                                    wire:click="completeRequest({{ $req->id }})">
                                                <i class="bi bi-check-circle"></i> Completed
                                            </button>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $sentRequests->links() }}
        </div>
    @else
        <div class="card text-center py-5 border-0 bg-light shadow-sm rounded-3">
            <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
            <h6 class="mt-2 text-muted">No requests found</h6>
        </div>
    @endif

    @include('livewire.partials.edit-request-modal')
</div>
