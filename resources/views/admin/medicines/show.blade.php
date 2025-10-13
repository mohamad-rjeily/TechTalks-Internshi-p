@extends('admin.layouts.app')

@section('title', 'Medicine Details')
@section('page-title', 'Medicine Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Medicine Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Medicine Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $medicine->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $medicine->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $medicine->category ? $medicine->category->name : 'No Category' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td>{{ $medicine->brand ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Form:</strong></td>
                                    <td>{{ $medicine->form ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Strength:</strong></td>
                                    <td>{{ $medicine->strength ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Condition Notes:</strong></td>
                                    <td>{{ $medicine->condition_notes ?: 'No notes' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $medicine->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $medicine->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Related Information</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This medicine belongs to the <strong>{{ $medicine->category ? $medicine->category->name : 'Uncategorized' }}</strong> category.
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Medicine
                        </a>
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Medicines
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
