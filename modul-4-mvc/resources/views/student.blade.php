@extends('layouts.adminlte-app')

@section('title', 'Student Dashboard')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">

            @if ($latestNotification)
                <div class="alert alert-{{ $latestNotification->is_read ? 'secondary' : 'warning' }}">
                    <strong>{{ $latestNotification->title }}</strong> – {{ $latestNotification->message }}
                    <a href="{{ route('notifications.show', $latestNotification->id) }}" class="btn btn-sm btn-outline-dark float-right">View</a>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-primary">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Bookings</span>
                            <span class="info-box-number">{{ $total }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <div class="info-box-content">
                            <span class="info-box-text">Approved</span>
                            <span class="info-box-number">{{ $approved }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <div class="info-box-content">
                            <span class="info-box-text">Rejected</span>
                            <span class="info-box-number">{{ $rejected }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('groups.create') }}" class="btn btn-outline-primary btn-block">Create Group</a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('bookings.create') }}" class="btn btn-outline-success btn-block">Make Booking</a>
                </div>
            </div>

        </div>
    </div>
@endsection
