<div
    x-data="{ open: @entangle('showCreateModal') }"
    x-init="
        const el = document.getElementById('createReportModal');
        if (!el) return;
        $watch('open', value => {
            if (value) {
                bootstrap.Modal.getOrCreateInstance(el).show();
                setTimeout(() => { el.querySelector('#target_type')?.focus(); }, 300);
            } else {
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }
        });

        el.addEventListener('hidden.bs.modal', () => {
            open = false;
            $wire.set('showCreateModal', false);
        });
    "
>
    <div wire:ignore.self class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form wire:submit.prevent="submit">
                    <div class="modal-header bg-success text-white rounded-top-3">
                        <h5 class="modal-title fw-bold" id="createReportLabel">
                            <i class="bi bi-flag-fill me-2"></i> Create Report
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="$set('showCreateModal', false)"></button>
                    </div>

                    <div class="modal-body p-4">
                        {{-- Target Type --}}
                        <div class="mb-3">
                            <label for="target_type" class="form-label fw-semibold">Target Type</label>
                            <select id="target_type" class="form-select shadow-sm @error('target_type') is-invalid @enderror"
                                    wire:model.live="target_type">
                                <option value="">Select type</option>
                                <option value="user">User</option>
                                <option value="medicine">Medicine</option>
                            </select>
                            @error('target_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Target Dropdown (always visible) --}}
                        <div class="mb-3">
                            <label for="target_id" class="form-label fw-semibold">Target</label>
                            <select id="target_id" class="form-select shadow-sm @error('target_id') is-invalid @enderror"
                                    wire:model="target_id" @if(!$target_type) disabled @endif>
                                <option value="">
                                    @if($target_type)
                                        Select {{ ucfirst($target_type) }}
                                    @else
                                        Select target type first
                                    @endif
                                </option>
                                @foreach ($targets as $target)
                                    <option value="{{ $target->id }}">{{ $target->name }}</option>
                                @endforeach
                            </select>
                            @error('target_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Reason --}}
                        <div class="mb-3">
                            <label for="reason" class="form-label fw-semibold">Reason</label>
                            <textarea id="reason" rows="4"
                                      wire:model.defer="reason"
                                      class="form-control shadow-sm @error('reason') is-invalid @enderror"
                                      placeholder="Describe the issue..."></textarea>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-outline-secondary shadow-sm" data-bs-dismiss="modal" wire:click="$set('showCreateModal', false)">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="bi bi-send me-1"></i> Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
