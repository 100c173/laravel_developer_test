@extends('dashboard.layouts.app')

@section('content')

    <!-- Statistics Cards -->
    @include('dashboard.includes.stats-cards')

    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="productsChart"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route('admin.dashboard.products-chart') }}')
                .then(res => res.json())
                .then(data => {
                    new Chart(document.getElementById('productsChart'), {
                        type: 'bar',
                        data: {
                            labels: data.map(d => d.date),
                            datasets: [{
                                label: 'Products',
                                data: data.map(d => d.count)
                            }]
                        }
                    });
                });
        });
    </script>
@endpush