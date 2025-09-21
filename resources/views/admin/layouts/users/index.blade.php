@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4>Manage Users</h4>
        <p class="text-muted">View and manage registered users</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-2"></i>Add New User
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Users</h6>
                    <h4 class="mb-0 text-primary">{{ $users->count() }}</h4>
                </div>
                <div class="text-primary" style="font-size: 2rem;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Verified Users</h6>
                    <h4 class="mb-0 text-success">{{ $users->where('email_verified_at', '!=', null)->count() }}</h4>
                </div>
                <div class="text-success" style="font-size: 2rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Admins</h6>
                    <h4 class="mb-0 text-warning">{{ $users->where('role', 'admin')->count() }}</h4>
                </div>
                <div class="text-warning" style="font-size: 2rem;">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">New This Week</h6>
                    <h4 class="mb-0 text-info">{{ $users->where('created_at', '>=', now()->subWeek())->count() }}</h4>
                </div>
                <div class="text-info" style="font-size: 2rem;">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="verificationFilter">
                    <option value="">All Users</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchUser" placeholder="Search users...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Users List
        </h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr data-role="{{ $user->role }}" data-verified="{{ $user->email_verified_at ? 'verified' : 'unverified' }}">
                        <td><span class="badge bg-primary">{{ $user->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->email_verified_at)
                                    <br><small class="text-success"><i class="fas fa-check-circle"></i> Verified</small>
                                    @else
                                    <br><small class="text-danger"><i class="fas fa-exclamation-circle"></i> Unverified</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: 'N/A' }}</td>
                        <td>{{ $user->location ?: 'N/A' }}</td>
                        <td>
                            @if($user->role === 'admin')
                            <span class="badge bg-danger">Admin</span>
                            @else
                            <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $user->created_at->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewUser({{ json_encode($user) }})"
                                        data-bs-toggle="modal" data-bs-target="#viewUserModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" 
                                        onclick="editUser({{ json_encode($user) }})"
                                        data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Users Found</h5>
        </div>
        @endif
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="userName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="userEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="userPassword" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="userPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="userPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="userPhone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="userLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="userLocation" name="location">
                    </div>
                    <div class="mb-3">
                        <label for="userRole" class="form-label">Role</label>
                        <select class="form-select" id="userRole" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 64px; height: 64px; font-size: 1.5rem;" id="viewUserInitial"></div>
                    <h4 class="mt-2 mb-0" id="viewUserName"></h4>
                    <p class="text-muted" id="viewUserEmail"></p>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Phone:</strong></div>
                    <div class="col-6" id="viewUserPhone"></div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Location:</strong></div>
                    <div class="col-6" id="viewUserLocation"></div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Role:</strong></div>
                    <div class="col-6" id="viewUserRole"></div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6" id="viewUserStatus"></div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Joined:</strong></div>
                    <div class="col-6" id="viewUserJoined"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal