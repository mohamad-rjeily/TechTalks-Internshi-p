<div>
    @if ($show)
        <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog"
             style="background: rgba(0,0,0,.5); z-index:1085;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <form wire:submit.prevent="submit">
                        <div class="modal-header bg-success text-white rounded-top-3">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-plus-circle me-1"></i> Create Request
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                    wire:click="$set('show', false)"></button>
                        </div>

                        <div class="modal-body p-4">
                            {{-- Medicine --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Medicine</label>

                                @if($lockMedicine)
                                    {{-- Locked: show read-only text and keep hidden id --}}
                                    <input type="hidden" wire:model="medicineId">
                                    <input type="text" class="form-control"
                                           value="{{ $medicineName }}"
                                           readonly
                                           style="pointer-events:none; background:#f8f9fa;">
                                @else
                                    {{-- Normal selectable --}}
                                    <select class="form-select @error('medicineId') is-invalid @enderror"
                                            wire:model="medicineId" required>
                                        <option value="">Select medicine</option>
                                        @foreach ($medicines as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicineId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @endif
                            </div>

                            {{-- Quantity --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Quantity</label>
                                <input type="number" min="1"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       wire:model="quantity" required>
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Message --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Message (optional)</label>
                                <textarea rows="3"
                                          class="form-control @error('message') is-invalid @enderror"
                                          wire:model="message"></textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-0 rounded-bottom-3">
                            <button type="button" class="btn btn-outline-secondary"
                                    wire:click="$set('show', false)">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <i class="bi bi-send me-1"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
