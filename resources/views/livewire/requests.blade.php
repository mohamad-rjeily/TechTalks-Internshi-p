<div>
    {{-- Page Header --}}
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-success">
                <i class="bi bi-list-check me-2"></i> Requests
            </h5>
            {{-- Create Request Button --}}
            <div class="d-flex justify-content-end">
                <button class="btn btn-success shadow-sm rounded-pill" wire:click="openCreateModal">
                    <i class="bi bi-plus-circle me-1"></i> Create Request
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
            <i class="bi {{ $flashType === 'success' 
                ? 'bi-check-circle-fill text-success' 
                : 'bi-exclamation-triangle-fill text-danger' }} me-2"></i>
            <div class="flex-grow-1 fw-semibold">{{ $flashMessage }}</div>
            <button type="button" class="btn-close" @click="show = false; $wire.hideFlash();"></button>
        </div>
    @endif

    {{-- Create Request Modal --}}
    @include('livewire.partials.create-request-modal')

    {{-- Tabs Card --}}
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-light border-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'my' ? 'active fw-bold text-success' : '' }}"
                            wire:click="$set('activeTab', 'my')">
                        <i class="bi bi-person-lines-fill me-1"></i> My Requests
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'pending' ? 'active fw-bold text-success' : '' }}"
                            wire:click="$set('activeTab', 'pending')">
                        <i class="bi bi-clock-history me-1"></i> Pending Requests
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-3">
            <div class="tab-content">
                @if ($activeTab === 'my')
                    <livewire:my-requests />
                @elseif ($activeTab === 'pending')
                    <livewire:pending-requests />
                @endif
            </div>
        </div>
    </div>
</div>