<div
    x-data="{ open: @entangle('showHelpModal') }"
    x-init="
        $watch('open', value => {
            const el = document.getElementById('helpModal');
            if (!el) return;
            if (value) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            } else {
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }
        });
    "
>
    <div wire:ignore.self class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">

                {{-- Header --}}
                <div class="modal-header bg-success text-white rounded-top-3">
                    <h5 class="modal-title fw-bold" id="helpModalLabel">
                        <i class="bi bi-heart-pulse-fill me-2"></i> Help Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" @click="open = false"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4">
                    @if ($helpRequest)
                        <div class="row g-4">
                            {{-- Requester Info --}}
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light shadow-sm h-100">
                                    <h6 class="fw-bold text-success mb-3">
                                        <i class="bi bi-person me-2"></i>Requester Info
                                    </h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><strong>Name:</strong> {{ $helpRequest->requester?->name }}</li>
                                        <li class="mb-2"><strong>Email:</strong> {{ $helpRequest->requester?->email }}</li>
                                        <li class="mb-2"><strong>Phone:</strong> {{ $helpRequest->requester?->phone ?? 'N/A' }}</li>
                                        <li><strong>Location:</strong> {{ $helpRequest->requester?->location ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Request Info --}}
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light shadow-sm h-100">
                                    <h6 class="fw-bold text-success mb-3">
                                        <i class="bi bi-capsule me-2"></i>Request Info
                                    </h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><strong>Medicine:</strong> {{ $helpRequest->medicine?->name }}</li>
                                        <li class="mb-2"><strong>Category:</strong> {{ $helpRequest->medicine?->category->name }}</li>
                                        <li class="mb-2"><strong>Strength:</strong> {{ $helpRequest->medicine?->strength ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Quantity Remaining:</strong>
                                            <span class="badge bg-success">{{ $helpRequest->quantity_remaining }}</span>
                                        </li>
                                        <li><strong>Message:</strong> {{ $helpRequest->message ?? '—' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Medicine Image --}}
                        <div class="mt-4 text-center">
                            @if ($helpRequest->medicine?->photo_path)
                                <img src="{{ asset('storage/' . $helpRequest->medicine->photo_path) }}"
                                    class="img-thumbnail shadow-sm"
                                    style="max-width: 240px;" alt="Medicine">
                            @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 120px; height: 120px;">
                                    <i class="bi bi-capsule text-muted" style="font-size: 2rem;"></i>
                                </div>
                                <p class="text-muted small mt-2">No image available</p>
                            @endif
                        </div>


                    @else
                        <div class="text-center py-4">
                            <div class="spinner-border text-success" role="status"></div>
                            <p class="text-muted mt-2">Loading request details...</p>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light border-0 rounded-bottom-3">
                    <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal" @click="open = false">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success shadow-sm" wire:click="acceptHelp">
                        <i class="bi bi-check-circle me-1"></i> Accept & Donate
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
