<div
    x-data="{ open: @entangle('showCreateModal') }"
    x-init="
        $watch('open', value => {
            const el = document.getElementById('createRequestModal');
            if (!el) return;
            if (value) {
                bootstrap.Modal.getOrCreateInstance(el).show();
                setTimeout(() => { el.querySelector('#medicine_id')?.focus(); }, 300);
            } else {
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }
        });
    "
>
    <div wire:ignore.self class="modal fade" id="createRequestModal" tabindex="-1" aria-labelledby="createRequestLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form wire:submit.prevent="submit">
                    {{-- Header --}}
                    <div class="modal-header bg-success text-white rounded-top-3">
                        <h5 class="modal-title fw-bold" id="createRequestLabel">
                            <i class="bi bi-plus-circle me-2"></i> Create New Request
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" @click="open = false; $wire.resetForm()"></button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body p-4">
                        {{-- Medicine --}}
                        <div class="mb-3">
                            <label for="medicine_id" class="form-label fw-semibold">Medicine <span class="text-danger">*</span></label>
                            <select class="form-select shadow-sm @error('medicine_id') is-invalid @enderror"
                                    wire:model="medicine_id" id="medicine_id">
                                <option value="">Select medicine</option>
                                @foreach ($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->category->name }})</option>
                                @endforeach
                            </select>
                            @error('medicine_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-3">
                            <label for="quantity_remaining" class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control shadow-sm @error('quantity_remaining') is-invalid @enderror"
                                wire:model="quantity_remaining" id="quantity_remaining">
                            @error('quantity_remaining') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Message --}}
                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">Message (optional)</label>
                            <textarea class="form-control shadow-sm @error('message') is-invalid @enderror"
                                    wire:model="message" id="message" rows="3"
                                    placeholder="Add extra details..."></textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer bg-light border-0 rounded-bottom-3">
                        <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal" @click="open = false; $wire.resetForm()">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="bi bi-send me-1"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
