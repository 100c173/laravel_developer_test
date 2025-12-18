@extends('dashboard.layouts.app')

@section('content')
    <div class="card overflow-hidden">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">
                    {{ __('Products') }}
                </h2>

                <a href="{{ route('admin.dashboard.products.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i>
                    {{ __('Add Product') }}
                </a>
            </div>

            {{-- Session Messages --}}
            @foreach (['success', 'error', 'warning', 'info'] as $type)
                @if (session($type))
                    <div class="alert alert-{{ $type }} mb-4">
                        {{ session($type) }}
                    </div>
                @endif
            @endforeach

            {{-- Table --}}
            <table id="products-table" class="table table-bordered w-full">
                <thead>
                    <tr>
                        <th class="border-r">ID</th>
                        <th class="border-r">{{ __('Title') }}</th>
                        <th class="border-r">{{ __('Primary Image') }}</th>
                        <th class="border-r">{{ __('Price') }}</th>
                        <th class="border-r">{{ __('Created At') }}</th>
                        <th class="border-r">{{ __('Updated At') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.dashboard.products.datatable') }}",
                dom: `
                                <"flex items-center justify-between mb-4"
                                    <"flex items-center gap-2"l>
                                    <"flex items-center gap-2"f>
                                >
                                rt
                                <"flex items-center justify-between mt-4"
                                    i
                                    p
                                >
                            `,
                language: {
                    search: "",
                    searchPlaceholder: "{{ __('Search products...') }}",
                    lengthMenu: "_MENU_ {{ __('per page') }}",
                    info: "{{ __('Showing') }} _START_ - _END_ {{ __('of') }} _TOTAL_",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                columns: [
                    { data: 'id', name: 'id', className: 'border-r' },
                    { data: 'title', name: 'title', className: 'border-r',searchable: false },
                    { data: 'primary_image', name: 'primary_image', orderable: false, searchable: false, className: 'border-r' },
                    { data: 'price', name: 'price', orderable: true, searchable: false, className: 'border-r' },
                    { data: 'created_at', name: 'created_at', orderable: true, searchable: false, className: 'border-r' },
                    { data: 'updated_at', name: 'updated_at', orderable: true, searchable: false, className: 'border-r' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                ]
            });

            // Delete handler
            $(document).on('click', '.action-delete', function () {
                const url = $(this).data('url');

                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('This action cannot be undone!') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "{{ __('Yes, delete it!') }}",
                    cancelButtonText: "{{ __('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                // تحديث الجدول بدون إعادة تحميل الصفحة
                                table.ajax.reload(null, false);

                                // رسالة نجاح
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message || "{{ __('Product deleted successfully') }}",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            },
                            error: function (xhr) {
                                let message = "{{ __('Something went wrong!') }}";

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: message
                                });
                            }
                        });
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
        /* Card */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        /* Table */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table thead th {
            background-color: #f8f9fa;
            padding: 0.75rem;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        .border-r {
            border-right: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        /* Action buttons */
        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-view {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .action-view:hover {
            background: #6c757d;
            color: #fff;
        }

        .action-edit {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .action-edit:hover {
            background: #007bff;
            color: #fff;
        }

        .action-delete {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .action-delete:hover {
            background: #dc3545;
            color: #fff;
        }

        /* Primary Image */
        .primary-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }

        /* Primary Image */
        .primary-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
@endpush