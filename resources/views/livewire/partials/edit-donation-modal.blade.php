<div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog"
     style="background: rgba(0,0,0,.5); z-index:1085;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white rounded-top-3">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil me-1"></i> Edit Donation
        </h5>
        <button type="button" class="btn-close btn-close-white"
                wire:click="cancelEdit"></button>
      </div>

      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold">Quantity</label>
          <input type="number" min="1"
                 class="form-control @error('editQuantity') is-invalid @enderror"
                 wire:model="editQuantity">
          @error('editQuantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Expiry date</label>
          <input type="date"
                 class="form-control @error('editExpiry') is-invalid @enderror"
                 wire:model="editExpiry">
          @error('editExpiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Notes</label>
          <textarea rows="3"
                    class="form-control @error('editNotes') is-invalid @enderror"
                    wire:model="editNotes"></textarea>
          @error('editNotes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="modal-footer bg-light border-0 rounded-bottom-3">
        <button type="button" class="btn btn-outline-secondary"
                wire:click="cancelEdit">Cancel</button>
        <button type="button" class="btn btn-primary"
                wire:click="updateDonation"
                wire:loading.attr="disabled">
          <i class="bi bi-save me-1"></i> Update
        </button>
      </div>
    </div>
  </div>
</div>
