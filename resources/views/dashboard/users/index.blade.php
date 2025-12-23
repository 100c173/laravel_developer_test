@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <!-- Main Card -->
            <div class="card">
                <div class="card-header p-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Users Management</h4>
                            <p class="text-muted mb-0">Manage user accounts and permissions</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="mb-3">
                                        <i class="fas fa-filter me-2"></i>
                                        Filter Users
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table id="users-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Verified</th>
                                    <th>Role</th>
                                    <th>Products</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Initialize DataTable
    const table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.dashboard.users.datatable') }}",
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'country' },
            { data: 'city' },
            { data: 'verified' },
            { data: 'role' },
            { data: 'products_count' },
            { data: 'created_at' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search users...",
            lengthMenu: "_MENU_ per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            paginate: {
                previous: "‹",
                next: "›"
            }
        }
    });

    // Apply filters
    $('.form-select').on('change', function() {
        table.ajax.reload();
    });

    // Delete user handler
    $(document).on('click', '.btn-delete', function() {
        const url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Something went wrong',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
$(document).ready(function() {
    // Initialize tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    // Delete user handler
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("Are you sure?") }}',
            text: '{{ __("This user will be permanently deleted!") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("Yes, delete it!") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#users-table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            '{{ __("Deleted!") }}',
                            response.message,
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            '{{ __("Error!") }}',
                            xhr.responseJSON?.message || '{{ __("Something went wrong") }}',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Block user handler
    $(document).on('click', '.block-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const userId = url.split('/').slice(-2, -1)[0];
        
        // Show modal for block duration
        $(`#blockModal-${userId}`).modal('show');
    });

    // Quick actions via AJAX
    function handleQuickAction(url, method = 'POST', data = {}) {
        $.ajax({
            url: url,
            type: method,
            data: {
                ...data,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#users-table').DataTable().ajax.reload(null, false);
                Swal.fire(
                    '{{ __("Success!") }}',
                    response.message,
                    'success'
                );
            },
            error: function(xhr) {
                Swal.fire(
                    '{{ __("Error!") }}',
                    xhr.responseJSON?.message || '{{ __("Something went wrong") }}',
                    'error'
                );
            }
        });
    }

    // Activate user
    $(document).on('click', '.activate-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("Activate User?") }}',
            text: '{{ __("This will activate the user account.") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("Yes, activate") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                handleQuickAction(url);
            }
        });
    });

    // Deactivate user
    $(document).on('click', '.deactivate-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("Deactivate User?") }}',
            text: '{{ __("This will deactivate the user account.") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("Yes, deactivate") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                handleQuickAction(url);
            }
        });
    });

    // Verify email
    $(document).on('click', '.verify-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("Verify Email?") }}',
            text: '{{ __("This will mark the user email as verified.") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("Yes, verify") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                handleQuickAction(url);
            }
        });
    });

    // Unverify email
    $(document).on('click', '.unverify-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("Unverify Email?") }}',
            text: '{{ __("This will mark the user email as unverified.") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fd7e14',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("Yes, unverify") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                handleQuickAction(url);
            }
        });
    });

    // Unblock user
    $(document).on('click', '.unblock-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        handleQuickAction(url);
    });

    // Handle block form submission
    $(document).on('submit', '[id^="blockForm-"]', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const formData = form.serialize();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                form.closest('.modal').modal('hide');
                $('#users-table').DataTable().ajax.reload(null, false);
                Swal.fire(
                    '{{ __("Blocked!") }}',
                    response.message,
                    'success'
                );
            },
            error: function(xhr) {
                Swal.fire(
                    '{{ __("Error!") }}',
                    xhr.responseJSON?.message || '{{ __("Something went wrong") }}',
                    'error'
                );
            }
        });
    });
});
</script>

<style>
.card.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.card.bg-gradient-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
}
.card.bg-gradient-warning {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
}
.card.bg-gradient-info {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table th {
    font-weight: 600;
    color: #4a5568;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endpush