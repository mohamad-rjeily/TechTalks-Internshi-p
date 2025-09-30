<div
    x-data="{ open: @entangle('showEditModal') }"
    x-init="
        $watch('open', value => {
            const el = document.getElementById('editRequestModal');
            if (!el) return;
            if (value) {
                bootstrap.Modal.getOrCreateInstance(el).show();
                setTimeout(() => { el.querySelector('#editQuantity')?.focus(); }, 300);
            } else {
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }
        });
    "
>
    <div wire:ignore.self class="modal fade" id="editRequestModal" tabindex="-1" aria-labelledby="editRequestLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form wire:submit.prevent="updateRequest">
                    {{-- Header --}}
                    <div class="modal-header bg-success text-white rounded-top-3">
                        <h5 class="modal-title fw-bold" id="editRequestLabel">
                            <i class="bi bi-pencil-square me-2"></i> Edit Request
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="cancelEdit"></button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body p-4">
                        @if ($editingReq)
                            {{-- Medicine Info --}}
                            <div class="mb-4 text-center">
                                @if (!empty($editingReq->medicine?->photo_path))
                                    <img src="{{ asset('storage/' . $editingReq->medicine->photo_path) }}"
                                         alt="Medicine"
                                         class="img-thumbnail mb-3 shadow-sm"
                                         style="max-height: 120px;">
                                @else
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width:80px; height:80px;">
                                        <i class="bi bi-capsule text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif

                                <div class="fw-bold text-primary h5">{{ $editingReq->medicine->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $editingReq->medicine->category->name ?? '' }}</div>
                            </div>

                            {{-- Quantity --}}
                            <div class="mb-3">
                                <label for="editQuantity" class="form-label fw-semibold">Quantity Remaining</label>
                                <input type="number" min="0"
                                       id="editQuantity"
                                       wire:model.defer="editQuantity"
                                       class="form-control shadow-sm @error('editQuantity') is-invalid @enderror">
                                @error('editQuantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Message --}}
                            <div class="mb-3">
                                <label for="editMessage" class="form-label fw-semibold">Message (optional)</label>
                                <textarea id="editMessage" rows="3"
                                          wire:model.defer="editMessage"
                                          class="form-control shadow-sm @error('editMessage') is-invalid @enderror"
                                          placeholder="Update your message..."></textarea>
                                @error('editMessage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No request selected.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal" wire:click="cancelEdit">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="bi bi-save me-1"></i> Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
