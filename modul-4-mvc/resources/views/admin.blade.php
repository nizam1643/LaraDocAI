@extends('layouts.adminlte-app')

@section('title', 'Admin Dashboard')

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

        <div class="card card-warning card-outline">
            <div class="card-header">
                <h5 class="m-0">Bookings Awaiting Approval</h5>
            </div>
            <div class="card-body">
                @forelse($pendingBookings as $booking)
                    <div class="mb-3">
                        <strong>{{ $booking->group->name }}</strong> requested <strong>{{ $booking->room->name }}</strong> on <strong>{{ $booking->date }}</strong>
                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning float-right">Review</a>
                    </div>
                @empty
                    <p>No pending bookings.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
