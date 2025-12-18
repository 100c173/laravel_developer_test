@extends('dashboard.layouts.app')

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Create New Product</h4>
                                <p class="text-muted mb-0">Add a new product to your store</p>
                            </div>
                            <a href="{{ route('admin.dashboard.products.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

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
                                           value="{{ old('title.en') }}" 
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
                                           value="{{ old('title.ar') }}" 
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
                                              placeholder="Enter detailed description in English">{{ old('description.en') }}</textarea>
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
                                              placeholder="أدخل وصف مفصل بالعربية">{{ old('description.ar') }}</textarea>
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
                                               value="{{ old('price') }}" 
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


                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="fas fa-redo me-2"></i>
                                            Reset
                                        </button>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>
                                                Create Product
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    #imagePreview .col-3 {
        margin-bottom: 10px;
    }

    #imagePreview img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
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

    small.text-muted {
        font-size: 0.875rem;
        color: #718096 !important;
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Generate slug from English title
    $('input[name="title[en]"]').on('input', function() {
        if (!$('#slug').attr('data-editing')) {
            const title = $(this).val().trim();
            const slug = title
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            $('#slug').val(slug);
        }
    });

    // Toggle slug edit mode
    $('#editSlug').click(function() {
        const slugInput = $('#slug');
        const isEditing = slugInput.attr('data-editing');
        
        if (isEditing) {
            slugInput.removeAttr('data-editing');
            slugInput.prop('readonly', true);
            $(this).html('<i class="fas fa-edit"></i>');
            $(this).removeClass('btn-success').addClass('btn-outline-secondary');
        } else {
            slugInput.attr('data-editing', 'true');
            slugInput.prop('readonly', false);
            slugInput.focus();
            $(this).html('<i class="fas fa-check"></i>');
            $(this).removeClass('btn-outline-secondary').addClass('btn-success');
        }
    });

    // Image preview
    $('input[name="images[]"]').change(function(e) {
        $('#imagePreview').empty();
        
        Array.from(e.target.files).forEach((file, index) => {
            if (!file.type.match('image.*')) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').append(`
                    <div class="col-6 col-md-3 position-relative">
                        <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-image" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });
    });

    // Remove image
    $(document).on('click', '.remove-image', function() {
        const index = $(this).data('index');
        const files = Array.from($('input[name="images[]"]')[0].files);
        files.splice(index, 1);
        
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        $('input[name="images[]"]')[0].files = dt.files;
        
        $(this).parent().remove();
    });

    // Auto focus first input
    if ($('input[name="title[en]"]').val() === '') {
        $('input[name="title[en]"]').focus();
    }
});
</script>
@endpush