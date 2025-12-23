@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- User Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Details</h5>
            <a href="{{ route('admin.dashboard.users.send-email', $user) }}" 
               class="btn btn-light btn-sm">
                <i class="fas fa-envelope me-1"></i> Send Email
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="120">Name:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Joined:</th>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <!-- Additional user info if needed -->
                </div>
            </div>
        </div>
    </div>

    <!-- Products Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Products</h6>
                    <h3 class="mb-0">{{ $productsData['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Value</h6>
                    <h3 class="mb-0">${{ number_format($productsData['total_value'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Average Price</h6>
                    <h3 class="mb-0">${{ number_format($productsData['average_price'], 2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Products Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0">User Products ({{ $productsData['total'] }})</h6>
        </div>
        <div class="card-body">
            @if($productsData['total'] > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="60">Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productsData['products'] as $product)
                                <tr>
                                    <td>
                                        @if($product->images->count() > 0)
                                            <img src="{{ asset($product->images->first()->url) }}" 
                                                 alt="Product" 
                                                 class="img-thumbnail" 
                                                 width="50" 
                                                 height="50">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5>No Products Found</h5>
                    <p class="text-muted">This user hasn't added any products yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        color: #6c757d;
    }
    .img-thumbnail {
        object-fit: cover;
        border-radius: 4px;
    }
</style>
@endsection