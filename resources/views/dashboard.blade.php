@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-success">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </h2>
                
                <!-- Notifications -->
                <div class="position-relative">
                    <button class="btn btn-outline-success position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <h6 class="dropdown-header">Notifications</h6>
                        @forelse($notifications as $notif)
                            <div class="dropdown-item-text {{ $notif['read_at'] ? 'text-muted' : 'bg-light' }}">
                                {{ $notif['message'] }}
                                <br><small class="text-muted">{{ $notif['created_at'] }}</small>
                            </div>
                        @empty
                            <div class="dropdown-item-text text-muted">No notifications</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-heart-fill display-4 mb-2"></i>
                    <h3 class="card-title">{{ $stats['total_donations'] }}</h3>
                    <p class="card-text">My Donations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-inbox-fill display-4 mb-2"></i>
                    <h3 class="card-title">{{ $stats['total_requests'] }}</h3>
                    <p class="card-text">My Requests</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill display-4 mb-2"></i>
                    <h3 class="card-title">{{ $stats['fulfilled_donations'] }}</h3>
                    <p class="card-text">Fulfilled</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill display-4 mb-2"></i>
                    <h3 class="card-title">{{ $stats['trust_score'] }}%</h3>
                    <p class="card-text">Trust Score</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Available Donations -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gift me-2"></i> Available Donations
                    </h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($availableDonations as $donation)
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $donation->medicine->name ?? 'Unknown' }}</h6>
                                    <p class="mb-1 text-muted">
                                        <strong>Quantity:</strong> {{ $donation->quantity }}<br>
                                        <strong>Expires:</strong> {{ \Carbon\Carbon::parse($donation->expiry_date)->format('d M Y') }}<br>
                                        <strong>Donor:</strong> {{ $donation->donor->name ?? 'Unknown' }}
                                    </p>
                                </div>
                                <a href="{{ route('donations.index') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-heart me-1"></i> Request
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-4"></i>
                            <p>No donations available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Open Requests -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-inbox me-2"></i> Open Requests
                    </h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($openRequests as $request)
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $request->requester->name ?? 'Unknown' }} needs {{ $request->medicine->name ?? 'Unknown Medicine' }}</h6>
                                    <p class="mb-1 text-muted">
                                        <strong>Remaining:</strong> {{ $request->quantity_remaining }}
                                    </p>
                                </div>
                                <a href="{{ route('requests.index') }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-hand-thumbs-up me-1"></i> Offer
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-4"></i>
                            <p>No open requests</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Urgent Requests -->
    @if($urgentRequests->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i> Urgent Requests
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($urgentRequests as $urgent)
                        <div class="alert alert-danger d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $urgent->requester->name ?? 'Someone' }}</strong> needs 
                                <strong>{{ $urgent->medicine_name }}</strong> 
                                ({{ $urgent->quantity_remaining }} left)
                            </div>
                            <a href="{{ route('requests.index') }}" class="btn btn-danger btn-sm">
                                <i class="bi bi-heart me-1"></i> Help
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i> Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentActivity as $activity)
                        <div class="border-bottom py-2">
                            @if($activity['type'] === 'donation')
                                <i class="bi bi-heart-fill text-success me-2"></i>
                                You donated <strong>{{ $activity['medicine_name'] }}</strong> 
                                (x{{ $activity['quantity'] }}) — Expires: {{ \Carbon\Carbon::parse($activity['expiry_date'])->format('d M Y') }}
                            @elseif($activity['type'] === 'request')
                                <i class="bi bi-inbox-fill text-info me-2"></i>
                                You requested <strong>{{ $activity['medicine_name'] }}</strong> 
                                (x{{ $activity['quantity'] }}) on {{ \Carbon\Carbon::parse($activity['created_at'])->format('d M Y H:i') }}
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-clock-history display-4"></i>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection