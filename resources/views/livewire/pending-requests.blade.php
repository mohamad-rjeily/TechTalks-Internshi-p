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
                <i class="bi bi-funnel-fill me-2"></i> Filter Pending Requests
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
                <div class="col-md-3">
                    <input type="text" class="form-control shadow-sm"
                        placeholder="Requester name"
                        wire:model.live="requesterFilter">
                </div>
                <div class="col-md-2">
                    <label for="fromDate" class="form-label text-muted small mb-1">From Date</label>
                    <input type="date" id="fromDate" class="form-control shadow-sm" wire:model.live="fromDate">
                </div>
                <div class="col-md-2">
                    <label for="toDate" class="form-label text-muted small mb-1">To Date</label>
                    <input type="date" id="toDate" class="form-control shadow-sm" wire:model.live="toDate">
                </div>
                <div class="col-md-2">
                    <div class="form-check d-flex align-items-center h-100">
                        <input type="checkbox" class="form-check-input mt-0 me-2" wire:model.live="urgentOnly" id="urgentOnly">
                        <label class="form-check-label mb-0" for="urgentOnly">Urgent only</label>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Pending Requests Table --}}
    @if ($pendingRequests->count() > 0)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold text-success">
                    <i class="bi bi-clock-history me-2"></i>Pending Requests ({{ $pendingRequests->total() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th class="text-center">Image</th>
                                <th class="text-center">Medicine</th>
                                <th class="text-center">Requester</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingRequests as $req)
                                <tr wire:key="pending-{{ $req->id }}">
                                    <td class="text-center">
                                        @if ($req->medicine?->photo_path)
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
                                    <td class="text-center fw-semibold ">
                                        {{ $req->requester?->name ?? '-' }}
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        {{ $req->quantity_remaining }}
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ $req->created_at?->format('Y-m-d') }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success shadow-sm me-1"
                                                wire:click="showHelp({{ $req->id }})">
                                            <i class="bi bi-heart-pulse-fill me-1"></i> Help
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $pendingRequests->links() }}
        </div>
    @else
        <div class="card text-center py-5 border-0 bg-light shadow-sm rounded-3">
            <i class="bi bi-check-circle text-muted" style="font-size: 2.5rem;"></i>
            <h6 class="mt-2 text-muted">No pending requests found</h6>
            <p class="text-muted small">All current requests have been processed.</p>
        </div>
    @endif

    {{-- Help Modal --}}
    @include('livewire.partials.help-modal')
</div>
