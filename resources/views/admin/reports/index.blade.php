@extends('admin.layouts.app')

@section('title', 'Reports Management')
@section('page-title', 'Reports Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h4>Manage Reports</h4>
        <p class="text-muted">View and manage user reports</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <select class="form-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchReport" placeholder="Search reports...">
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-times me-2"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>
</div>

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
                        <th>Target ID</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr data-status="{{ $report->status }}">
                        <td><span class="badge bg-primary">{{ $report->id }}</span></td>
                        <td>
                            @if($report->reporter)
                                <strong>{{ $report->reporter->name }}</strong>
                                <br><small class="text-muted">{{ $report->reporter->email }}</small>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </td>
                        <td>{{ $report->target_id }}</td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $report->reason }}">
                                {{ $report->reason }}
                            </span>
                        </td>
                        <td>
                            @if($report->status === 'open')
                                <span class="badge bg-warning text-dark">Open</span>
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
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteReport({{ $report->id }})">
                                    <i class="fas fa-trash"></i>
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

<!-- View Report Modal -->
<div class="modal fade" id="viewReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Report Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Report ID:</strong>
                        <p id="viewReportId"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p id="viewReportStatus"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Reporter:</strong>
                        <p id="viewReportReporter"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Target ID:</strong>
                        <p id="viewReportTarget"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Reason:</strong>
                    <p id="viewReportReason"></p>
                </div>
                <div class="mb-3">
                    <strong>Admin Notes:</strong>
                    <p id="viewReportNotes" class="text-muted"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Created:</strong>
                        <p id="viewReportCreated"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Updated:</strong>
                        <p id="viewReportUpdated"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Report Modal -->
<div class="modal fade" id="resolveReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Resolve Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="resolveReportForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Mark this report as resolved?</p>
                    <div class="mb-3">
                        <label for="adminNote" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="adminNote" name="admin_note" rows="3" 
                                  placeholder="Add any notes about the resolution"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Resolve Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this report?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-warning me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteReportForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewReport(report) {
    document.getElementById('viewReportId').textContent = report.id;
    document.getElementById('viewReportStatus').innerHTML = report.status === 'open' 
        ? '<span class="badge bg-warning text-dark">Open</span>' 
        : '<span class="badge bg-success">Resolved</span>';
    document.getElementById('viewReportReporter').textContent = report.reporter ? report.reporter.name : 'Unknown';
    document.getElementById('viewReportTarget').textContent = report.target_id;
    document.getElementById('viewReportReason').textContent = report.reason;
    document.getElementById('viewReportNotes').textContent = report.admin_note || 'No notes';
    document.getElementById('viewReportCreated').textContent = new Date(report.created_at).toLocaleString();
    document.getElementById('viewReportUpdated').textContent = new Date(report.updated_at).toLocaleString();
}

function resolveReport(id) {
    document.getElementById('resolveReportForm').action = '{{ url("admin/reports") }}/' + id;
}

function deleteReport(id) {
    document.getElementById('deleteReportForm').action = '{{ url("admin/reports") }}/' + id;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteReportModal'));
    deleteModal.show();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchReport').value = '';
    filterReports();
}

function filterReports() {
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
document.getElementById('statusFilter').addEventListener('change', filterReports);
document.getElementById('searchReport').addEventListener('input', filterReports);
</script>
@endpush
