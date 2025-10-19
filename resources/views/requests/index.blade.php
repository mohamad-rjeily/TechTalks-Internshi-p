@extends('layouts.app')

@section('content')
    {{-- Success Notification --}}
    @if(isset($showSuccessAlert) && $showSuccessAlert)
        <div id="successAlert" class="position-fixed top-0 end-0 p-3" style="z-index: 9999; display: none;">
            <div class="alert alert-success border-0 shadow-sm rounded-pill d-flex align-items-center" style="min-width: 300px; max-width: 400px;">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <div class="fw-semibold small">Request submitted successfully!</div>
            </div>
        </div>
    @endif

    <livewire:requests />

    @if(isset($showSuccessAlert) && $showSuccessAlert)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('successAlert');
                if (alert) {
                    // Slide in from right
                    alert.style.display = 'block';
                    alert.style.transform = 'translateX(100%)';
                    alert.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    
                    // Animate in
                    setTimeout(() => {
                        alert.style.transform = 'translateX(0)';
                        alert.style.opacity = '1';
                    }, 50);
                    
                    // Auto-hide after 3 seconds
                    setTimeout(() => {
                        alert.style.transform = 'translateX(100%)';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    }, 3000);
                }
            });
        </script>
    @endif
@endsection