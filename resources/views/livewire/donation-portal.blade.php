<div>
  @if ($showCreate)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="background: rgba(0,0,0,.5); z-index:1085;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
          <div class="modal-header bg-success text-white rounded-top-3">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-plus-circle me-1"></i> Create Donation
            </h5>
            <button type="button" class="btn-close btn-close-white" wire:click="$set('showCreate', false)"></button>
          </div>

          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Medicine</label>
              @if($createMedicineLocked)
                <input type="hidden" wire:model="createMedicineId">
                <input type="text" class="form-control" value="{{ optional(collect($medicines)->firstWhere('id', (int)$createMedicineId))->name }}" readonly>
                <div class="form-text text-success">Selected from Browse Medicines</div>
              @else
                <select class="form-select @error('createMedicineId') is-invalid @enderror" wire:model="createMedicineId" required>
                  <option value="">Select medicine</option>
                  @foreach ($medicines as $m)
                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                  @endforeach
                </select>
                @error('createMedicineId') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Quantity</label>
              <input type="number" min="1" class="form-control @error('createQuantity') is-invalid @enderror" wire:model="createQuantity" required>
              @error('createQuantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Expiry date</label>
              <input type="date" class="form-control @error('createExpiry') is-invalid @enderror" wire:model="createExpiry" required>
              @error('createExpiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Notes</label>
              <textarea rows="3" class="form-control @error('createNotes') is-invalid @enderror" wire:model="createNotes"></textarea>
              @error('createNotes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="modal-footer bg-light border-0 rounded-bottom-3">
            <button type="button" class="btn btn-outline-secondary" wire:click="resetCreateForm; $set('showCreate', false)">Cancel</button>
            <button type="button" class="btn btn-success" wire:click="create" wire:loading.attr="disabled">
              <i class="bi bi-send me-1"></i> Save
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>


