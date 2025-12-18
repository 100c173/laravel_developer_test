@extends('dashboard.layouts.app')

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Edit Product</h4>
                                <p class="text-muted mb-0">Update product information</p>
                            </div>
                            <a href="{{ route('admin.dashboard.products.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Products
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Title Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i class="fas fa-heading text-primary me-2"></i>
                                        Product Title
                                    </h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        English Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title.en') is-invalid @enderror" 
                                           name="title[en]" 
                                           value="{{ old('title.en', $product->getTranslation('title', 'en')) }}" 
                                           placeholder="Enter product title in English">
                                    @error('title.en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Arabic Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title.ar') is-invalid @enderror" 
                                           name="title[ar]" 
                                           value="{{ old('title.ar', $product->getTranslation('title', 'ar')) }}" 
                                           placeholder="أدخل عنوان المنتج بالعربية">
                                    @error('title.ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i class="fas fa-align-left text-primary me-2"></i>
                                        Product Description
                                    </h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        English Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description.en') is-invalid @enderror" 
                                              name="description[en]" 
                                              rows="3" 
                                              placeholder="Enter detailed description in English">{{ old('description.en', $product->getTranslation('description', 'en')) }}</textarea>
                                    @error('description.en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Arabic Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description.ar') is-invalid @enderror" 
                                              name="description[ar]" 
                                              rows="3" 
                                              placeholder="أدخل وصف مفصل بالعربية">{{ old('description.ar', $product->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description.ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price & Images -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3">
                                        <i class="fas fa-tag text-primary me-2"></i>
                                        Pricing
                                    </h5>
                                    <label class="form-label">
                                        Price <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               name="price" 
                                               value="{{ old('price', $product->price) }}" 
                                               min="0" 
                                               step="0.01" 
                                               placeholder="0.00">
                                        <span class="input-group-text">USD</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Enter the price in US dollars</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3">
                                        <i class="fas fa-images text-primary me-2"></i>
                                        Product Images
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        To manage product images, please use the 
                                        <a href="{{ route('admin.dashboard.products.images.index', $product) }}" class="alert-link">
                                            Images Gallery
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Images Preview -->
                            @if($product->images->count() > 0)
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">
                                            <i class="fas fa-photo-video text-primary me-2"></i>
                                            Current Images
                                        </h5>
                                        <div class="row g-3">
                                            @foreach($product->images as $image)
                                                <div class="col-6 col-md-3">
                                                    <div class="card position-relative border">
                                                        <img src="{{ $image->url }}" 
                                                             class="card-img-top" 
                                                             alt="Product Image"
                                                             style="height: 120px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                @if($image->is_primary)
                                                                    <span class="badge bg-primary">
                                                                        <i class="fas fa-star me-1"></i> Primary
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary">
                                                                        Secondary
                                                                    </span>
                                                                @endif
                                                                <a href="{{ route('admin.dashboard.products.images.index', $product) }}"
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Product Status -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">
                                        <i class="fas fa-toggle-on text-primary me-2"></i>
                                        Product Status
                                    </h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1"
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Product
                                        </label>
                                    </div>
                                    <small class="text-muted">When inactive, product won't be visible to customers</small>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <!-- Delete Button with Confirmation -->
                                            <button type="button" 
                                                    class="btn btn-danger"
                                                    onclick="confirmDelete()">
                                                <i class="fas fa-trash me-2"></i>
                                                Delete Product
                                            </button>
                                        </div>
                                        <div>
                                            <button type="reset" class="btn btn-secondary me-2">
                                                <i class="fas fa-redo me-2"></i>
                                                Reset Changes
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>
                                                Update Product
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Hidden Delete Form -->
                        <form id="delete-form" 
                              action="{{ route('admin.dashboard.products.destroy', $product) }}" 
                              method="POST" 
                              class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
    }

    .card-header h4 {
        color: white;
        font-weight: 600;
    }

    .card-header p {
        color: rgba(255, 255, 255, 0.8);
    }

    .card-header .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .card-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .form-label {
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group-text {
        background-color: #f7fafc;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-weight: 500;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .input-group-text:first-child {
        border-right: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    h5 {
        color: #2d3748;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    h5 i {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .alert-info {
        background-color: #ebf8ff;
        border: 1px solid #bee3f8;
        color: #2c5282;
        border-radius: 8px;
    }

    .alert-info .alert-link {
        color: #2b6cb0;
        text-decoration: underline;
    }

    .alert-info .alert-link:hover {
        color: #2c5282;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .btn-danger {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 101, 101, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }

    .btn-secondary {
        background: #e2e8f0;
        border: none;
        color: #4a5568;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .btn-outline-primary {
        border: 1px solid #667eea;
        color: #667eea;
        background: transparent;
        padding: 5px 10px;
        border-radius: 6px;
    }

    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }

    small.text-muted {
        font-size: 0.875rem;
        color: #718096 !important;
    }

    /* Image Cards */
    .position-relative .badge {
        position: absolute;
        top: 10px;
        left: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .row .col-md-6 {
            margin-bottom: 1.5rem;
        }
    }

    /* Animation */
    .card {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Grid spacing */
    .g-3 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto focus first input
    $('input[name="title[en]"]').focus();

    // Form reset handler
    $('button[type="reset"]').click(function() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'All changes will be lost. Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reset it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Reset form values to original
                const form = $('form')[0];
                form.reset();
                
                // Reset checkboxes to original state
                $('#is_active').prop('checked', {{ $product->is_active ? 'true' : 'false' }});
                
                // Show success message
                Swal.fire(
                    'Reset!',
                    'Form has been reset to original values.',
                    'success'
                );
            }
        });
    });
});

// Delete confirmation
function confirmDelete() {
    Swal.fire({
        title: 'Delete Product?',
        text: "This will permanently delete the product and all its images. This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 1000);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}

// Prevent form submission on Enter key
$(document).on('keypress', 'input, textarea', function(e) {
    if (e.which === 13 && !$(this).is('textarea')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush