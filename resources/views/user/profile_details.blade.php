<div class="card mb-5 border-success border-2 shadow-lg rounded-4 overflow-hidden">
    
    {{-- EN-TÊTE : Banderole de couleur VERT CLAIR pour l'élégance et la propreté --}}
    <div class="card-header text-success bg-success-subtle py-4 border-0">
        <div class="text-center">
            <h3 class="fw-bolder mb-0">{{ $profileUser->name }}</h3>
        </div>
    </div>
    
    <div class="card-body p-5 bg-white">
        
        {{-- SECTION STATUT (Adhésion/Disponibilité) --}}
        <div class="text-center mb-5 pb-4 border-bottom border-success-subtle">
            @if ($profileUser->newsletter_opt_in)
                <span class="badge bg-success text-white py-2 px-4 rounded-pill fs-6 fw-bold">
                    <i class="fas fa-check-circle me-2"></i> Health News Subscriber
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary py-2 px-4 rounded-pill fs-6 fw-bold">
                    Updates Disabled
                </span>
            @endif
        </div>

        <h5 class="text-secondary mb-4 border-bottom pb-2">Contact Information</h5>
        
        {{-- CONTENEUR DES DÉTAILS : Utilisation de la list-group pour la clarté --}}
        <div class="list-group list-group-flush rounded-3 border">
            
            {{-- Email --}}
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-normal text-muted">Email</span>
                <span class="fw-semibold">
                    <a href="mailto:{{ $profileUser->email }}" class="text-dark">{{ $profileUser->email }}</a>
                </span>
            </div>

            {{-- Location --}}
            <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <span class="fw-normal text-muted">Location</span>
                <span class="fw-semibold">
                    @if ($profileUser->location)
                        {{ $profileUser->location }}
                    @else
                        <span class="text-warning fst-italic">Not specified</span>
                    @endif
                </span>
            </div>
            
            {{-- Phone --}}
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-normal text-muted">Phone</span>
                <span class="fw-semibold">
                    @if ($profileUser->phone)
                        <a href="tel:{{ $profileUser->phone }}" class="text-dark">{{ $profileUser->phone }}</a>
                    @else
                        <span class="text-secondary fst-italic">N/A</span>
                    @endif
                </span>
            </div>

        </div> 
        
    </div>
</div>