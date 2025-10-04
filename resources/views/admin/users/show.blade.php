@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">User Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Name:</strong> {{ $user->name }}
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Location:</strong> {{ $user->location ?? 'N/A' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Role:</strong> {{ ucfirst($user->role) }}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    @if($user->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-danger">Not Verified</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}
                </div>
                <div class="col-md-6">
                    <strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y') }}
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>
</div>
@endsection
