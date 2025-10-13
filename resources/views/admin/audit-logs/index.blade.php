@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h4>System Audit Logs</h4>
        <p class="text-muted">Track all system activities for transparency and accountability</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Logs</h6>
                    <h4 class="mb-0 text-primary">{{ $totalLogs }}</h4>
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
                    <h4 class="mb-0 text-success">{{ $todayLogs }}</h4>
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
                    <h4 class="mb-0 text-info">{{ $weekLogs }}</h4>
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
                    <h4 class="mb-0 text-danger">{{ $criticalLogs }}</h4>
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
        <form id="filterForm" method="GET" action="{{ route('admin.audit-logs.index') }}">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">Action Type</label>
                    <select class="form-select" name="action_type" id="actionFilter">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action_type') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action_type') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action_type') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="login" {{ request('action_type') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action_type') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="banned" {{ request('action_type') == 'banned' ? 'selected' : '' }}>Banned</option>
                        <option value="suspended" {{ request('action_type') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Target Type</label>
                    <select class="form-select" name="target_type" id="targetFilter">
                        <option value="">All Targets</option>
                        <option value="user" {{ request('target_type') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="medicine" {{ request('target_type') == 'medicine' ? 'selected' : '' }}>Medicine</option>
                        <option value="category" {{ request('target_type') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="report" {{ request('target_type') == 'report' ? 'selected' : '' }}>Report</option>
                        <option value="request" {{ request('target_type') == 'request' ? 'selected' : '' }}>Request</option>
                        <option value="donation" {{ request('target_type') == 'donation' ? 'selected' : '' }}>Donation</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" class="form-control" name="date_from" id="dateFrom" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" class="form-control" name="date_to" id="dateTo" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" id="searchLogs" placeholder="Search logs..." value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>
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
                <tbody id="auditTableBody">
                    @foreach($logs as $log)
                    <tr>
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
                                        @case('logout')
                                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" 
                                                style="width: 24px; height: 24px;">
                                                <i class="fas fa-sign-out-alt fa-xs"></i>
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
                                    @case('logout') bg-warning text-dark @break
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
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Audit Logs Found</h5>
            <p class="text-muted">
                @if(request()->hasAny(['action_type', 'target_type', 'date_from', 'date_to', 'search']))
                    No logs match your filter criteria. Try adjusting your filters.
                @else
                    No system activities have been logged yet
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="logDetailsContent"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script loaded successfully');
        
        // Refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                console.log('Refresh button clicked');
                window.location.reload();
            });
        } else {
            console.error('Refresh button not found');
        }
        
        // Export button
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                console.log('Export button clicked');
                exportLogsToCSV();
            });
        } else {
            console.error('Export button not found');
        }
        
        // Auto-submit form when filters change
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');
            
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            // For search input, submit on Enter key
            const searchInput = document.getElementById('searchLogs');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterForm.submit();
                    }
                });
            }
        }
    });
    
    function exportLogsToCSV() {
        console.log('Starting export...');
        
        const table = document.getElementById('auditTable');
        if (!table) {
            console.error('Table not found');
            alert('No table found to export!');
            return;
        }
        
        const tbody = table.querySelector('tbody');
        if (!tbody) {
            console.error('Table body not found');
            alert('No table body found!');
            return;
        }
        
        const rows = tbody.querySelectorAll('tr');
        console.log('Found ' + rows.length + ' rows');
        
        if (rows.length === 0) {
            alert('No logs to export!');
            return;
        }

        let csvContent = "Timestamp,Actor,Action,Target,Details\n";
        
        rows.forEach(function(row, index) {
            const cells = row.querySelectorAll('td');
            console.log('Row ' + index + ' has ' + cells.length + ' cells');
            
            if (cells.length >= 5) {
                const timestamp = cells[0].textContent.trim().replace(/\s+/g, ' ');
                const actor = cells[1].textContent.trim().replace(/\s+/g, ' ');
                const action = cells[2].textContent.trim();
                const target = cells[3].textContent.trim().replace(/\s+/g, ' ');
                const details = cells[4].textContent.trim();
                
                csvContent += '"' + timestamp + '","' + actor + '","' + action + '","' + target + '","' + details + '"\n';
            }
        });

        console.log('CSV content created, length: ' + csvContent.length);

        // Create blob and download
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        
        link.setAttribute("href", url);
        link.setAttribute("download", "audit_logs_" + new Date().toISOString().split('T')[0] + ".csv");
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        console.log('Link created, triggering download...');
        link.click();
        document.body.removeChild(link);
        
        console.log('Export completed!');
        alert('Logs exported successfully!');
    }

    function viewLogDetails(log) {
        const modalContent = document.getElementById('logDetailsContent');
        if (modalContent) {
            modalContent.textContent = JSON.stringify(log, null, 2);
        }
    }
</script>
@endsection