@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4>System Audit Logs</h4>
        <p class="text-muted">Track all system activities for transparency and accountability</p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="exportLogs()">
                <i class="fas fa-download me-2"></i>Export Logs
            </button>
            <button type="button" class="btn btn-outline-info" onclick="refreshLogs()">
                <i class="fas fa-sync me-2"></i>Refresh
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Logs</h6>
                    <h4 class="mb-0 text-primary">{{ $logs->total() }}</h4>
                </div>
                <div class="text-primary" style="font-size: 2rem;">
                    <i class="fas fa-history"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Today</h6>
                    <h4 class="mb-0 text-success">{{ $logs->where('created_at', '>=', now()->startOfDay())->count() }}</h4>
                </div>
                <div class="text-success" style="font-size: 2rem;">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">This Week</h6>
                    <h4 class="mb-0 text-info">{{ $logs->where('created_at', '>=', now()->startOfWeek())->count() }}</h4>
                </div>
                <div class="text-info" style="font-size: 2rem;">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Critical Actions</h6>
                    <h4 class="mb-0 text-danger">{{ $logs->whereIn('action_type', ['deleted', 'banned', 'suspended'])->count() }}</h4>
                </div>
                <div class="text-danger" style="font-size: 2rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-filter me-2"></i>Advanced Filters
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <label class="form-label">Action Type</label>
                <select class="form-select" id="actionFilter">
                    <option value="">All Actions</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="banned">Banned</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Target Type</label>
                <select class="form-select" id="targetFilter">
                    <option value="">All Targets</option>
                    <option value="user">User</option>
                    <option value="medicine">Medicine</option>
                    <option value="category">Category</option>
                    <option value="report">Report</option>
                    <option value="request">Request</option>
                    <option value="donation">Donation</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" class="form-control" id="dateFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" class="form-control" id="dateTo">
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" id="searchLogs" placeholder="Search logs...">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="clearLogFilters()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Activity Timeline
        </h5>
    </div>
    <div class="card-body">
        @if($logs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="auditTable">
                <thead class="table-light">
                    <tr>
                        <th>Timestamp</th>
                        <th>Actor</th>
                        <th>Action</th>
                        <th>Target</th>
                        <th>Details</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr data-action="{{ $log->action_type }}" 
                        data-target="{{ $log->target_type }}" 
                        data-date="{{ $log->created_at->format('Y-m-d') }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @switch($log->action_type)
                                        @case('created')
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-plus fa-xs"></i>
                                            </div>
                                            @break
                                        @case('updated')
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-edit fa-xs"></i>
                                            </div>
                                            @break
                                        @case('deleted')
                                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-trash fa-xs"></i>
                                            </div>
                                            @break
                                        @case('login')
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-sign-in-alt fa-xs"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-info fa-xs"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div>
                                    <strong>{{ $log->created_at->format('M j, Y') }}</strong><br>
                                    <small class="text-muted">{{ $log->created_at->format('g:i A') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 28px; height: 28px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($log->actor->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $log->actor->name ?? 'System' }}</strong><br>
                                    <small class="text-muted">ID: {{ $log->actor_id ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @switch($log->action_type)
                                    @case('created') bg-success @break
                                    @case('updated') bg-primary @break
                                    @case('deleted') bg-danger @break
                                    @case('login') bg-info @break
                                    @case('logout') bg-warning @break
                                    @default bg-secondary
                                @endswitch
                            ">
                                {{ ucfirst($log->action_type) }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <span class="badge bg-light text-dark">{{ ucfirst($log->target_type) }}</span>
                                @if($log->target_id)
                                <br><small class="text-muted">ID: {{ $log->target_id }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;">
                                @if(is_array($log->detail))
                                    {{ json_encode($log->detail) }}
                                @else
                                    {{ $log->detail ?? 'No details' }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info" 
                                    onclick="viewLogDetails({{ json_encode($log) }})"
                                    data-bs-toggle="modal" data-bs-target="#logDetailsModal">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Audit Logs Found</h5>
            <p class="text-muted">No system activities have been logged yet</p>
        </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade