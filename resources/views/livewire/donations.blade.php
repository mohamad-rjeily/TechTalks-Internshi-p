<div>
    {{-- Header --}}
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-success">
                <i class="bi bi-heart-fill me-2"></i> Donations
            </h5>
            <button class="btn btn-success shadow-sm rounded-pill"
                    wire:click="$set('showCreateModal', true)">
                <i class="bi bi-plus-circle me-1"></i> Create Donation
            </button>
        </div>
    </div>

    {{-- Flash --}}
    @if ($flashVisible && $flashMessage)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.hideFlash(); }, 3500)"
             x-show="show" x-transition
             class="alert alert-{{ $flashType }} border-0 shadow-sm rounded-3">
            <div class="fw-semibold">{{ $flashMessage }}</div>
        </div>
    @endif

    <div class="card shadow border-0 rounded-3">
        {{-- Tabs --}}
        <div class="card-header bg-light border-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'my' ? 'active fw-bold text-success' : '' }}"
                            wire:click="$set('activeTab', 'my')">
                        <i class="bi bi-box-seam me-1"></i> My Donations
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'pending' ? 'active fw-bold text-success' : '' }}"
                            wire:click="$set('activeTab', 'pending')">
                        <i class="bi bi-clock-history me-1"></i> Pending Donations
                    </button>
                </li>
            </ul>
        </div>

        {{-- Filters --}}
        <div class="px-3 pt-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Medicine</label>
                    <select class="form-select"
                            wire:key="medFilter-{{ $filtersNonce ?? 0 }}"
                            wire:model.live="medicineFilter">
                        <option value="">All Medicines</option>
                        @foreach ($medicines as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Status</label>
                    <select class="form-select"
                            wire:key="statusFilter-{{ $filtersNonce ?? 0 }}"
                            wire:model.live="statusFilter">
                        <option value="all">All Statuses</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">From Date (Created)</label>
                    <input type="date"
                           class="form-control"
                           wire:key="fromDate-{{ $filtersNonce ?? 0 }}"
                           wire:model.live="fromDate">
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label mb-1">To Date (Expiry ≤)</label>
                    <input type="date"
                           class="form-control"
                           wire:key="toDate-{{ $filtersNonce ?? 0 }}"
                           wire:model.live="toDate">
                </div>

                <div class="col-12 col-md-1">
                    <label class="form-label d-none d-md-block mb-1">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100"
                            wire:click="resetFilters">
                        Reset
                    </button>
                </div>
            </div>
            <hr class="mt-3 mb-0">
        </div>

        {{-- Tables --}}
        <div class="card-body p-3">
            <div class="tab-content">
                @if ($activeTab === 'my')
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Expiry</th>
                                <th>Recipient</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($myDonations as $don)
                                <tr>
                                    <td>{{ $don->id }}</td>
                                    <td>{{ $don->medicine?->name }}</td>
                                    <td class="fw-semibold text-success">{{ $don->quantity }}</td>
                                    <td>
                                        @php
                                            $computed = ($don->expiry_date && \Illuminate\Support\Carbon::parse($don->expiry_date)->isPast())
                                                ? 'expired'
                                                : ($don->status ?? 'unknown');
                                            $badgeClass = match($computed) {
                                                'available'   => 'bg-success-subtle text-success',
                                                'unavailable' => 'bg-danger-subtle text-danger',
                                                'expired'     => 'bg-warning-subtle text-warning',
                                                default       => 'bg-secondary-subtle',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                            {{ ucfirst($computed) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($don->expiry_date)->format('Y-m-d') }}</td>
                                    <td>{{ $don->recipient?->name ?? '—' }}</td>
                                    <td class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                                wire:click="startEdit({{ $don->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger"
                                                wire:click="cancelDonation({{ $don->id }})">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No donations found</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 d-flex justify-content-center">{{ $myDonations->links() }}</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Donor</th>
                                <th>Quantity</th>
                                <th>Expiry</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($pendingDonations as $don)
                                <tr>
                                    <td>{{ $don->id }}</td>
                                    <td>{{ $don->medicine?->name }}</td>
                                    <td>{{ $don->donor?->name ?? '—' }}</td>
                                    <td class="fw-semibold text-success">{{ $don->quantity }}</td>
                                    <td>{{ optional($don->expiry_date)->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No pending donations</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 d-flex justify-content-center">{{ $pendingDonations->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    @if ($showCreateModal)
        @include('livewire.partials.create-donation-modal')
    @endif

    {{-- EDIT MODAL --}}
    @if ($showEditModal)
        @include('livewire.partials.edit-donation-modal')
    @endif
</div>
