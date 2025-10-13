@extends('admin.layouts.app')

@section('title', 'Medicines Management')
@section('page-title', 'Medicines Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4>Manage Medicines</h4>
        <p class="text-muted">Add, edit, or delete medicines in the system</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
            <i class="fas fa-plus me-2"></i>Add New Medicine
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchMedicine" placeholder="Search medicines...">
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-times me-2"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Medicines Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-pills me-2"></i>Medicines List
        </h5>
    </div>
    <div class="card-body">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="medicinesTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Medicine Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Form</th>
                        <th>Strength</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr data-category="{{ $medicine->category_id }}">
                        <td><span class="badge bg-primary">{{ $medicine->id }}</span></td>
                        <td>
                            <strong>{{ $medicine->name }}</strong>
                            @if($medicine->photo_path)
                            <br><small class="text-success"><i class="fas fa-image"></i> Has photo</small>
                            @endif
                        </td>
                        <td>{{ $medicine->brand ?: 'N/A' }}</td>
                        <td>
                            @if($medicine->category)
                            <span class="badge bg-info">{{ $medicine->category->name }}</span>
                            @else
                            <span class="badge bg-secondary">No Category</span>
                            @endif
                        </td>
                        <td>{{ $medicine->form ?: 'N/A' }}</td>
                        <td>{{ $medicine->strength ?: 'N/A' }}</td>
                        <td>
                            <small class="text-muted">
                                {{ $medicine->created_at->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        onclick="editMedicine({{ json_encode($medicine) }})"
                                        data-bs-toggle="modal" data-bs-target="#editMedicineModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteMedicine({{ $medicine->id }}, '{{ $medicine->name }}')">
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
            <i class="fas fa-pills fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Medicines Found</h5>
            <p class="text-muted">Start by adding your first medicine</p>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                <i class="fas fa-plus me-2"></i>Add First Medicine
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Add New Medicine
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.medicines.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medicineName" class="form-label">Medicine Name *</label>
                            <input type="text" class="form-control" id="medicineName" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="medicineCategory" class="form-label">Category *</label>
                            <select class="form-select" id="medicineCategory" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medicineBrand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="medicineBrand" name="brand">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="medicineForm" class="form-label">Form</label>
                            <select class="form-select" id="medicineForm" name="form">
                                <option value="">Select Form</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="syrup">Syrup</option>
                                <option value="injection">Injection</option>
                                <option value="cream">Cream</option>
                                <option value="drops">Drops</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medicineStrength" class="form-label">Strength</label>
                            <input type="text" class="form-control" id="medicineStrength" name="strength" 
                                   placeholder="e.g., 500mg, 5ml">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="medicinePhoto" class="form-label">Medicine Photo</label>
                            <input type="file" class="form-control" id="medicinePhoto" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="medicineNotes" class="form-label">Condition Notes</label>
                        <textarea class="form-control" id="medicineNotes" name="condition_notes" rows="3" 
                                  placeholder="Any special conditions or notes about this medicine"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Medicine Modal -->
<div class="modal fade" id="editMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Medicine
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editMedicineForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editMedicineName" class="form-label">Medicine Name *</label>
                            <input type="text" class="form-control" id="editMedicineName" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editMedicineCategory" class="form-label">Category *</label>
                            <select class="form-select" id="editMedicineCategory" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editMedicineBrand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="editMedicineBrand" name="brand">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editMedicineForm" class="form-label">Form</label>
                            <select class="form-select" id="editMedicineForm" name="form">
                                <option value="">Select Form</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="syrup">Syrup</option>
                                <option value="injection">Injection</option>
                                <option value="cream">Cream</option>
                                <option value="drops">Drops</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editMedicineStrength" class="form-label">Strength</label>
                            <input type="text" class="form-control" id="editMedicineStrength" name="strength">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editMedicinePhoto" class="form-label">Update Photo</label>
                            <input type="file" class="form-control" id="editMedicinePhoto" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editMedicineNotes" class="form-label">Condition Notes</label>
                        <textarea class="form-control" id="editMedicineNotes" name="condition_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMedicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the medicine <strong id="deleteMedicineName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-warning me-2"></i>
                    This action cannot be undone. All related donations and requests will be affected.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteMedicineForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Medicine
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editMedicine(medicine) {
    document.getElementById('editMedicineName').value = medicine.name;
    document.getElementById('editMedicineCategory').value = medicine.category_id;
    document.getElementById('editMedicineBrand').value = medicine.brand || '';
    document.getElementById('editMedicineForm').value = medicine.form || '';
    document.getElementById('editMedicineStrength').value = medicine.strength || '';
    document.getElementById('editMedicineNotes').value = medicine.condition_notes || '';
    document.getElementById('editMedicineForm').action = '{{ url("admin/medicines") }}/' + medicine.id;
}

function deleteMedicine(id, name) {
    document.getElementById('deleteMedicineName').textContent = name;
    document.getElementById('deleteMedicineForm').action = '{{ url("admin/medicines") }}/' + id;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteMedicineModal'));
    deleteModal.show();
}

function clearFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('searchMedicine').value = '';
    filterMedicines();
}

function filterMedicines() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const searchFilter = document.getElementById('searchMedicine').value.toLowerCase();
    const rows = document.querySelectorAll('#medicinesTable tbody tr');
    
    rows.forEach(row => {
        const categoryMatch = !categoryFilter || row.dataset.category === categoryFilter;
        const nameMatch = !searchFilter || row.textContent.toLowerCase().includes(searchFilter);
        
        row.style.display = categoryMatch && nameMatch ? '' : 'none';
    });
}

// Add event listeners
document.getElementById('categoryFilter').addEventListener('change', filterMedicines);
document.getElementById('searchMedicine').addEventListener('input', filterMedicines);
</script>
@endpush