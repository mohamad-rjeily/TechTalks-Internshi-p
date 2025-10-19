<div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Donations</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($donations->count() > 0)
                <div class="row">
                    @foreach($donations as $donation)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $donation->medicine->name ?? 'Unknown Medicine' }}</h5>
                                    <p class="card-text">
                                        <strong>Quantity:</strong> {{ $donation->quantity }}<br>
                                        <strong>Status:</strong> 
                                        <span class="badge {{ $donation->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($donation->status) }}
                                        </span><br>
                                        <strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($donation->expiry_date)->format('M d, Y') }}<br>
                                        <strong>Donor:</strong> {{ $donation->donor->name ?? 'Unknown' }}<br>
                                        @if($donation->recipient)
                                            <strong>Recipient:</strong> {{ $donation->recipient->name }}<br>
                                        @endif
                                        @if($donation->notes)
                                            <strong>Notes:</strong> {{ $donation->notes }}
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        Created: {{ $donation->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <h3>No donations available</h3>
                    <p class="text-muted">There are currently no donations to display.</p>
                </div>
            @endif
        </div>
    </div>
</div>