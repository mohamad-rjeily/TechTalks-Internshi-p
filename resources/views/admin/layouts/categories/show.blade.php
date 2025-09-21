@extends('admin.layouts.app')

@section('title', 'Category Details')
@section('page-title', 'Category Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Category Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $category->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $category->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistics</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This category contains <strong>{{ $category->medicines_count ?? 0 }}</strong> medicines.
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

