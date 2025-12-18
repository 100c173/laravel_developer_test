@extends('dashboard.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Activity Logs</h3>
        </div>
        <div class="card-body">
            <table id="activityLogsTable" class="table table-bordered w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Log Name</th>
                        <th>Description</th>
                        <th>Subject</th>
                        <th>Causer</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#activityLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.dashboard.activity-logs.datatable') }}",
                    type: "GET",
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'log_name', name: 'log_name' },
                    { data: 'description', name: 'description' },
                    { data: 'subject', orderable: false, searchable: false },
                    { data: 'causer', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                ],

            });
        });
    </script>
@endpush