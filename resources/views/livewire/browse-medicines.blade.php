<div>
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold text-success mb-3">
                    <i class="bi bi-capsule-pill me-3"></i>Browse Medicines
                </h1>
                <p class="lead text-muted">Find the medicines you need or donate the ones you have</p>
            </div>
        </div>
    </div>

    {{-- Search and Filter Section --}}
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                {{-- Search Bar --}}
                <div class="col-12 col-md-8">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" 
                               class="form-control form-control-lg ps-5 border-0 rounded-pill shadow-sm" 
                               placeholder="Search medicines by name, brand, form, or strength..."
                               wire:model.live.debounce.300ms="search"
                               style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="col-12 col-md-3">
                    <select class="form-select form-select-lg border-0 rounded-pill shadow-sm" 
                            wire:model.live="selectedCategory"
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Clear Filters --}}
                <div class="col-12 col-md-1">
                    <button class="btn btn-outline-secondary btn-lg w-100 rounded-pill" 
                            wire:click="clearFilters"
                            title="Clear all filters">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>

            {{-- Search Results Count --}}
            @if($search || $selectedCategory)
                <div class="mt-3">
                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                        <i class="bi bi-funnel me-1"></i>
                        {{ $medicines->count() }} medicine{{ $medicines->count() !== 1 ? 's' : '' }} found
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 3500)" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="alert alert-success border-0 shadow-sm rounded-pill mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Livewire Components --}}
    <livewire:request-create-modal />
    <livewire:donation-portal />

    {{-- Medicines Grid --}}
    <div class="row g-4">
        @forelse($medicines as $medicine)
            <div class="col-12 col-sm-6 col-lg-4" 
                 x-data="{ hover: false }"
                 x-on:mouseenter="hover = true"
                 x-on:mouseleave="hover = false">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden"
                     :class="{ 'shadow-lg': hover }"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    {{-- Card Header with Gradient --}}
                    <div class="card-header border-0 py-3" 
                         style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                <i class="bi bi-capsule text-white fs-5"></i>
                            </div>
                            <div class="text-white">
                                <h5 class="card-title mb-0 fw-bold">{{ $medicine->name }}</h5>
                                <small class="opacity-75">{{ $medicine->category?->name ?? 'Uncategorized' }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-tag-fill text-success me-2"></i>
                                    <small class="text-muted">Brand</small>
                                </div>
                                <p class="mb-0 fw-semibold">{{ $medicine->brand ?? '—' }}</p>
                            </div>
                            
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-shape-fill text-primary me-2"></i>
                                    <small class="text-muted">Form</small>
                                </div>
                                <p class="mb-0 fw-semibold">{{ $medicine->form ?? '—' }}</p>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-lightning-fill text-warning me-2"></i>
                                    <small class="text-muted">Strength</small>
                                </div>
                                <p class="mb-0 fw-semibold">{{ $medicine->strength ?? '—' }}</p>
                            </div>
                        </div>

                        {{-- Condition Notes --}}
                        @if($medicine->condition_notes)
                            <div class="mt-3 p-3 bg-light rounded-3">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Notes:</strong> {{ $medicine->condition_notes }}
                                </small>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer with Action Buttons --}}
                    <div class="card-footer border-0 bg-light p-4">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg rounded-pill fw-semibold"
                                    onclick="Livewire.dispatch('openRequestFromBrowse', [{{ $medicine->id }}])">
                                <i class="bi bi-heart me-2"></i>Request Medicine
                            </button>
                            <button class="btn btn-outline-success btn-lg rounded-pill fw-semibold"
                                    onclick="Livewire.dispatch('openDonationFromBrowse', [{{ $medicine->id }}])">
                                <i class="bi bi-gift me-2"></i>Donate Medicine
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">
                        @if($search || $selectedCategory)
                            No medicines found
                        @else
                            No medicines available
                        @endif
                    </h3>
                    <p class="text-muted mb-4">
                        @if($search || $selectedCategory)
                            Try adjusting your search criteria or filters
                        @else
                            Check back later for available medicines
                        @endif
                    </p>
                    @if($search || $selectedCategory)
                        <button class="btn btn-outline-primary rounded-pill" wire:click="clearFilters">
                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                        </button>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="text-center py-4">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">Searching medicines...</p>
    </div>
</div>
