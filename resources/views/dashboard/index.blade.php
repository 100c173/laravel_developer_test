@extends('dashboard.layouts.app')

@section('content')
    <!-- Welcome Section -->
    @include('dashboard.includes.welcome')

    <!-- Statistics Cards -->
    @include('dashboard.includes.stats-cards')

    <!-- يمكنك إضافة المزيد من المحتوى هنا -->
    {{-- @yield('dashboard-content') --}}
@endsection