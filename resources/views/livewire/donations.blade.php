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

    {{-- Success Notification --}}
    @if ($flashVisible && $flashMessage)
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; $wire.hideFlash(); }, 3000)"
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
             class="position-fixed top-0 end-0 p-3" 
                    style="z-index: 9999;">
            <div class="alert alert-{{ $flashType }} border-0 shadow-sm rounded-pill d-flex align-items-center" 
                 style="min-width: 300px; max-width: 400px;">
                <i class="bi {{ $flashType === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger' }} me-2"></i>
                <div class="fw-semibold small">{{ $flashMessage }}</div>
            </div>
        </div>
    @endif

    {{-- Session Flash Notification (from browse medicine) --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => { show = false; }, 3000)"
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
             class="position-fixed top-0 end-0 p-3" 
                    style="z-index: 9999;">
            <div class="alert alert-success border-0 shadow-sm rounded-pill d-flex align-items-center" 
                 style="min-width: 300px; max-width: 400px;">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <div class="fw-semibold small">{{ session('success') }}</div>
            </div>
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
                                    <td>
                                        @if($don->expiry_date)
                                            {{ \Carbon\Carbon::parse($don->expiry_date)->format('M d, Y') }}
                                            @if(\Carbon\Carbon::parse($don->expiry_date)->isPast())
                                                <small class="text-danger">(Expired)</small>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $don->recipient?->name ?? '—' }}</td>
                                    <td class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                                wire:click="startEdit({{ $don->id }})"
                                                title="Edit Donation">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger"
                                                wire:click="cancelDonation({{ $don->id }})"
                                                wire:confirm="Are you sure you want to delete this donation?"
                                                title="Delete Donation">
                                            <i class="bi bi-trash me-1"></i> Delete
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
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($pendingDonations as $don)
                                <tr>
                                    <td>{{ $don->id }}</td>
                                    <td>{{ $don->medicine?->name }}</td>
                                    <td>{{ $don->donor?->name ?? '—' }}</td>
                                    <td class="fw-semibold text-success">{{ $don->quantity }}</td>
                                    <td>
                                        @if($don->expiry_date)
                                            {{ \Carbon\Carbon::parse($don->expiry_date)->format('M d, Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success"
                                                wire:click="openRequestModal({{ $don->id }})">
                                            <i class="bi bi-heart me-1"></i> Request
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No pending donations</td></tr>
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

    {{-- REQUEST MODAL --}}
    @if ($showRequestModal)
        @include('livewire.partials.request-donation-modal')
    @endif
</div>
