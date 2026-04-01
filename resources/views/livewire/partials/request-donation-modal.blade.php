<div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog"
     style="background: rgba(0,0,0,.5); z-index:1085;">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      
      {{-- Modal Header --}}
      <div class="modal-header bg-success text-white rounded-top-3">
        <div class="d-flex align-items-center">
          <i class="bi bi-heart-pulse me-2"></i>
          <h5 class="modal-title fw-bold mb-0">Help Request</h5>
        </div>
        <div class="d-flex align-items-center">
          <span class="badge bg-warning me-2">Urgent only</span>
          <button type="button" class="btn-close btn-close-white" wire:click="cancelRequest"></button>
        </div>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body p-4">
        @php
          $donation = \App\Models\Donation::with(['medicine', 'donor', 'medicine.category'])->find($requestDonationId);
        @endphp
        
        @if($donation)
          <div class="row">
            {{-- Donor Info Column --}}
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100">
                <h6 class="fw-bold text-success mb-3">
                  <i class="bi bi-person me-2"></i>Donor Info
                </h6>
                <div class="mb-2">
                  <strong>Name:</strong> {{ $donation->donor?->name ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Email:</strong> {{ $donation->donor?->email ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Phone:</strong> {{ $donation->donor?->phone ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Location:</strong> {{ $donation->donor?->location ?? '—' }}
                </div>
              </div>
            </div>

            {{-- Request Info Column --}}
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100">
                <h6 class="fw-bold text-success mb-3">
                  <i class="bi bi-capsule me-2"></i>Request Info
                </h6>
                <div class="mb-2">
                  <strong>Medicine:</strong> {{ $donation->medicine?->name ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Category:</strong> {{ $donation->medicine?->category?->name ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Strength:</strong> {{ $donation->medicine?->strength ?? '—' }}
                </div>
                <div class="mb-2">
                  <strong>Quantity Available:</strong> 
                  <span class="badge bg-success rounded-pill">{{ $donation->quantity }}</span>
                </div>
                <div class="mb-2">
                  <strong>Expiry:</strong> {{ optional($donation->expiry_date)->format('Y-m-d') ?? '—' }}
                </div>
                <div class="mb-3">
                  <strong>Quantity Needed:</strong>
                  <input type="number" 
                         min="1" 
                         max="{{ $donation->quantity }}"
                         class="form-control mt-2 @error('requestQuantity') is-invalid @enderror"
                         wire:model="requestQuantity"
                         placeholder="Enter quantity needed">
                  @error('requestQuantity') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                  @enderror
                  <div class="form-text">Maximum: {{ $donation->quantity }} units</div>
                </div>
                <div class="mb-3">
                  <strong>Message:</strong>
                  <textarea class="form-control mt-2 @error('requestMessage') is-invalid @enderror"
                            wire:model="requestMessage" 
                            rows="3" 
                            placeholder="Please explain why you need this medicine..."></textarea>
                  @error('requestMessage') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Medicine Image Placeholder --}}
          <div class="row mt-4">
            <div class="col-12">
              <div class="border rounded-3 p-4 text-center" style="background-color: #f8f9fa;">
                <i class="bi bi-capsule text-muted" style="font-size: 3rem;"></i>
                <div class="text-muted mt-2">No image available</div>
              </div>
            </div>
          </div>
        @endif
      </div>

      {{-- Modal Footer --}}
      <div class="modal-footer bg-light border-0 rounded-bottom-3">
        <button type="button" class="btn btn-outline-secondary"
                wire:click="cancelRequest">
          <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-success"
                wire:click="submitRequest" 
                wire:loading.attr="disabled">
          <i class="bi bi-check-circle me-1"></i> Request
        </button>
      </div>

    </div>
  </div>
</div>
