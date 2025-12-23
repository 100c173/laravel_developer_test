@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card overflow-hidden">
            <div class="p-6">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold">
                            {{ __('Product Images Gallery') }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $product->title }} - ID: {{ $product->id }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard.products.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            {{ __('Back to Products') }}
                        </a>
                        <a href="{{ route('admin.dashboard.products.edit', $product) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit mr-1"></i>
                            {{ __('Edit Product') }}
                        </a>
                    </div>
                </div>

                {{-- Session Messages --}}
                @foreach (['success', 'error', 'warning', 'info'] as $type)
                    @if (session($type))
                        <div class="alert alert-{{ $type }} mb-4">
                            {{ session($type) }}
                        </div>
                    @endif
                @endforeach

                {{-- Add Image Form --}}
                <div class="card mb-6">
                    <div class="p-4">
                        <h3 class="font-medium text-gray-700 mb-3">{{ __('Add New Images') }}</h3>
                        {{-- Display Form Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-error mb-4">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.dashboard.products.images.store', $product) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ __('You can select multiple images. Max size: 2MB per image.') }}
                                    </p>
                                    @error('images')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    {{ __('Upload Images') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Images Gallery --}}
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach($product->images as $image)
                            <div class="card border rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 {{ $image->is_primary ? 'ring-2 ring-blue-500' : '' }}">
                                {{-- Image --}}
                                <div class="relative">
                                    <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ __('Product Image') }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                        >

                                    {{-- Primary Badge --}}
                                    @if($image->is_primary)
                                        <div class="absolute top-2 left-2">
                                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                                <i class="fas fa-star mr-1"></i> {{ __('Primary') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Image Info --}}
                                <div class="p-3 border-t">
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ __('Uploaded') }}: {{ $image->created_at->format('Y-m-d H:i') }}
                                    </div>
                                    
                                    {{-- Action Buttons (under the image) --}}
                                    <div class="flex items-center justify-between gap-2 mt-3">
                                        {{-- Set as Primary Button --}}
                                        @if(!$image->is_primary)
                                            <form action="{{ route('admin.dashboard.products.images.primary', $image) }}" 
                                                  method="POST" 
                                                  class="flex-1">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" 
                                                        class="w-full btn btn-sm btn-outline-yellow flex items-center justify-center gap-1"
                                                        title="{{ __('Set as Primary') }}">
                                                    <i class="fas fa-star text-xs"></i>
                                                    <span class="text-xs">{{ __('Set Primary') }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex-1">
                                                <button type="button" 
                                                        class="w-full btn btn-sm btn-success flex items-center justify-center gap-1 cursor-default"
                                                        disabled>
                                                    <i class="fas fa-check text-xs"></i>
                                                    <span class="text-xs">{{ __('Primary') }}</span>
                                                </button>
                                            </div>
                                        @endif

                                        {{-- Delete Button --}}
                                        <div class="flex-1">
                                            <button type="button"
                                                    class="w-full btn btn-sm btn-outline-red flex items-center justify-center gap-1 delete-btn"
                                                    title="{{ __('Delete Image') }}"
                                                    data-form-id="delete-form-{{ $image->id }}">
                                                <i class="fas fa-trash text-xs"></i>
                                                <span class="text-xs">{{ __('Delete') }}</span>
                                            </button>
                                            
                                            {{-- Hidden Delete Form --}}
                                            <form id="delete-form-{{ $image->id }}"
                                                  action="{{ route('admin.dashboard.products.images.destroy', $image) }}" 
                                                  method="POST" 
                                                  class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- No Images --}}
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-5xl mb-4">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">
                            {{ __('No Images Found') }}
                        </h3>
                        <p class="text-gray-500">
                            {{ __('Upload images using the form above.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Delete image confirmation
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                const formId = $(this).data('form-id');
                const form = document.getElementById(formId);

                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('This image will be permanently deleted!') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "{{ __('Yes, delete it!') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                $('.alert').fadeOut(500, function () {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    <style>
        /* Card Styles */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
        }

        /* Alert Styles */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left-color: #10b981;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border-left-color: #f59e0b;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border-left-color: #3b82f6;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-outline-primary {
            border-color: #3b82f6;
            color: #3b82f6;
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: #3b82f6;
            color: white;
        }

        .btn-outline-secondary {
            border-color: #6b7280;
            color: #6b7280;
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: #6b7280;
            color: white;
        }

        .btn-outline-yellow {
            border-color: #eab308;
            color: #eab308;
            background-color: transparent;
        }

        .btn-outline-yellow:hover {
            background-color: #eab308;
            color: white;
        }

        .btn-outline-red {
            border-color: #ef4444;
            color: #ef4444;
            background-color: transparent;
        }

        .btn-outline-red:hover {
            background-color: #ef4444;
            color: white;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
        }

        /* Grid */
        .grid {
            display: grid;
        }

        /* File Input */
        input[type="file"]::file-selector-button {
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #dbeafe;
        }

        /* Image Card Hover */
        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Object Cover for Images */
        .object-cover {
            object-fit: cover;
        }

        /* Hidden class */
        .hidden {
            display: none;
        }

        /* Flex utilities */
        .flex-1 {
            flex: 1 1 0%;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        /* Border top */
        .border-t {
            border-top: 1px solid #e5e7eb;
        }

        /* Margin utilities */
        .mt-3 {
            margin-top: 0.75rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush