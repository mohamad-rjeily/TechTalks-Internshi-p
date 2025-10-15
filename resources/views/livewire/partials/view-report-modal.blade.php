<div
    x-data="{ open: @entangle('showViewModal') }"
    x-init="
        const el = document.getElementById('viewReportModal');
        if (!el) return;
        $watch('open', value => {
            if (value) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            } else {
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }
        });
        el.addEventListener('hidden.bs.modal', () => {
            open = false;
            $wire.set('showViewModal', false);
        });
    "
>
    <div wire:ignore.self class="modal fade" id="viewReportModal" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-success text-white rounded-top-3">
                    <h5 class="modal-title fw-bold" id="viewReportLabel">
                        <i class="bi bi-eye-fill me-2"></i> Report Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="cancelView"></button>
                </div>

                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    @if ($viewingReport)
                        {{-- Target Info --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">Reported Target</h6>
                            @if ($viewingReport->target_type === 'user' && $viewingReport->target)
                                <div class="border rounded p-3 bg-light">
                                    <div><strong>Name:</strong> {{ $viewingReport->target->name }}</div>
                                    <div><strong>Email:</strong> {{ $viewingReport->target->email }}</div>
                                    <div><strong>Phone:</strong> {{ $viewingReport->target->phone ?? '—' }}</div>
                                    <div><strong>Location:</strong> {{ $viewingReport->target->location ?? '—' }}</div>
                                </div>
                            @elseif ($viewingReport->target_type === 'medicine' && $viewingReport->target)
                                <div class="border rounded p-3 bg-light">
                                    <div><strong>Name:</strong> {{ $viewingReport->target->name }}</div>
                                    <div><strong>Brand:</strong> {{ $viewingReport->target->brand ?? '—' }}</div>
                                    <div><strong>Category:</strong> {{ $viewingReport->target->category->name ?? '—' }}</div>
                                    <div><strong>Form:</strong> {{ $viewingReport->target->form ?? '—' }}</div>
                                    <div><strong>Strength:</strong> {{ $viewingReport->target->strength ?? '—' }}</div>
                                    <div><strong>Condition Notes:</strong> {{ $viewingReport->target->condition_notes ?? '—' }}</div>
                                </div>
                            @else
                                <div class="text-muted">Target information not available.</div>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-control-plaintext">
                                <span class="badge rounded-pill px-3 py-2 shadow-sm
                                    {{ $viewingReport->status === 'open' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }}">
                                    <i class="bi {{ $viewingReport->status === 'open' ? 'bi-hourglass-split' : 'bi-check-circle-fill' }} me-1"></i>
                                    {{ ucfirst($viewingReport->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason</label>
                            <div class="form-control border bg-light p-3 rounded shadow-sm"
                                 style="word-break: break-word; max-height: 300px; overflow-y: auto;">
                                {{ $viewingReport->reason }}
                            </div>
                        </div>

                        {{-- Admin Notes (only if resolved) --}}
                        @if ($viewingReport->status === 'resolved' && $viewingReport->admin_notes)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Admin Notes</label>
                                <div class="form-control border bg-light p-3 rounded shadow-sm text-muted"
                                     style="word-break: break-word; max-height: 300px; overflow-y: auto;">
                                    {{ $viewingReport->admin_notes }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No report selected.</p>
                        </div>
                    @endif
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-3">
                    <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal" wire:click="cancelView">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
