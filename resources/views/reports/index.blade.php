@extends('admin.layouts.app')

@section('title', 'Reports Management')
@section('page-title', 'Reports Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4>Manage Reports</h4>
        <p class="text-muted">Review and resolve user reports</p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="filterReports('open')">
                <i class="fas fa-flag me-2"></i>Open Reports
            </button>
            <button type="button" class="btn btn-outline-success" onclick="filterReports('resolved')">
                <i class="fas fa-check me-2"></i>Resolved Reports
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="filterReports('')">
                <i class="fas fa-list me-2"></i>All Reports
            </button>
        </div>
    </div>
</div>

---

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Reports</h6>
                    <h4 class="mb-0 text-primary">{{ $reports->count() }}</h4>
                </div>
                <div class="text-primary" style="font-size: 2rem;">
                    <i class="fas fa-flag"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Open Reports</h6>
                    <h4 class="mb-0 text-danger">{{ $reports->where('status', 'open')->count() }}</h4>
                </div>
                <div class="text-danger" style="font-size: 2rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Resolved</h6>
                    <h4 class="mb-0 text-success">{{ $reports->where('status', 'resolved')->count() }}</h4>
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
                    <h6 class="text-muted mb-1">This Week</h6>
                    <h4 class="mb-0 text-info">{{ $reports->where('created_at', '>=', now()->subWeek())->count() }}</h4>
                </div>
                <div class="text-info" style="font-size: 2rem;">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
</div>

---

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="open">Open</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" id="searchReport" placeholder="Search reports...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

---

<!-- Reports Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-flag me-2"></i>Reports List
        </h5>
    </div>
    <div class="card-body">
        @if($reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="reportsTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Target</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Reported</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr data-status="{{ $report->status }}" data-reason="{{ strtolower($report->reason) }}">
                        <td><span class="badge bg-primary">{{ $report->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                    style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($report->reporter->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $report->reporter->name ?? 'Unknown User' }}</strong>
                                    <br><small class="text-muted">{{ $report->reporter->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">Target ID: {{ $report->target_id }}</span>
                        </td>
                        <td>
                            <!-- Reason Badge Logic -->
                            @php
                                $badgeClass = 'bg-secondary';
                                switch($report->reason) {
                                    case 'spam':
                                    case 'inappropriate':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'abuse':
                                    case 'fraud':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    default:
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($report->reason) }}
                            </span>
                        </td>
                        <td>
                            @if($report->status === 'open')
                            <span class="badge bg-danger">Open</span>
                            @else
                            <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $report->created_at->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-info" 
                                        onclick="viewReport({{ json_encode($report) }})"
                                        data-bs-toggle="modal" data-bs-target="#viewReportModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($report->status === 'open')
                                <button type="button" class="btn btn-outline-success" 
                                        onclick="resolveReport({{ $report->id }})"
                                        data-bs-toggle="modal" data-bs-target="#resolveReportModal">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-outline-primary" 
                                        onclick="addNote({{ $report->id }}, '{{ $report->admin_note ?? '' }}')"
                                        data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-flag fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Reports Found</h5>
            <p class="text-muted">No reports have been submitted yet</p>
        </div>
        @endif
    </div>
</div>

---

<!-- View Report Modal -->
<div class="modal fade" id="viewReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-flag me-2"></i>Report Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Reporter Information</h6>
                        <div class="bg-light p-3 rounded mb-3">
                            <strong id="viewReporterName"></strong><br>
                            <small class="text-muted" id="viewReporterEmail"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Report Status</h6>
                        <div class="bg-light p-3 rounded mb-3">
                            <span id="viewReportStatus"></span><br>
                            <small class="text-muted" id="viewReportDate"></small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Reason</h6>
                    <div class="bg-light p-3 rounded">
                        <span id="viewReportReason"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Target Information</h6>
                    <div class="bg-light p-3 rounded">
                        Target ID: <span id="viewReportTarget"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Admin Note</h6>
                    <div class="bg-light p-3 rounded">
                        <span id="viewAdminNote">No admin note added</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

---

<!-- Resolve Report Modal -->
<div class="modal fade" id="resolveReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check me-2"></i>Resolve Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="resolveReportForm">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <input type="hidden" name="status" value="resolved">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Mark this report as resolved. This action cannot be undone.
                    </div>
                    <div class="mb-3">
                        <label for="resolveNote" class="form-label">Admin Note (Optional)</label>
                        <textarea class="form-control" id="resolveNote" name="admin_note" rows="3" 
                                    placeholder="Add a note about how this report was resolved..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Mark as Resolved
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

---

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-comment me-2"></i>Add Admin Note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="addNoteForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adminNote" class="form-label">Admin Note</label>
                        <textarea class="form-control" id="adminNote" name="admin_note" rows="4" 
                                    placeholder="Add your note or reply here..."></textarea>
                        <div class="form-text">This note will be visible to other administrators.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewReport(report) {
    document.getElementById('viewReporterName').textContent = report.reporter?.name || 'Unknown User';
    document.getElementById('viewReporterEmail').textContent = report.reporter?.email || 'N/A';
    document.getElementById('viewReportStatus').innerHTML = report.status === 'open' ? 
        '<span class="badge bg-danger">Open</span>' : '<span class="badge bg-success">Resolved</span>';
    document.getElementById('viewReportDate').textContent = new Date(report.created_at).toLocaleString();
    document.getElementById('viewReportReason').textContent = report.reason;
    document.getElementById('viewReportTarget').textContent = report.target_id;
    document.getElementById('viewAdminNote').textContent = report.admin_note || 'No admin note added';
}

function resolveReport(id) {
    document.getElementById('resolveReportForm').action = '{{ url("admin/reports") }}/' + id + '/resolve';
    document.getElementById('resolveNote').value = '';
}

function addNote(id, currentNote) {
    document.getElementById('adminNote').value = currentNote;
    document.getElementById('addNoteForm').action = '{{ url("admin/reports") }}/' + id;
}

function filterReports(status) {
    document.getElementById('statusFilter').value = status;
    applyFilters();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchReport').value = '';
    applyFilters();
}

function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchFilter = document.getElementById('searchReport').value.toLowerCase();
    const rows = document.querySelectorAll('#reportsTable tbody tr');
    
    rows.forEach(row => {
        const statusMatch = !statusFilter || row.dataset.status === statusFilter;
        const textMatch = !searchFilter || row.textContent.toLowerCase().includes(searchFilter);
        
        row.style.display = statusMatch && textMatch ? '' : 'none';
    });
}

// Add event listeners
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('searchReport').addEventListener('input', applyFilters);
</script>
@endpush