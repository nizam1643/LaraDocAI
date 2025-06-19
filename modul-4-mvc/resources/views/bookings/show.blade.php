@extends('layouts.adminlte-app')

@section('title', 'Booking Details')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title') GENERAL</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Booking Detail</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Group:</strong> {{ $booking->group->name }}</p>
                            <p><strong>Room:</strong> {{ $booking->room->name }}</p>
                            <p><strong>Date:</strong> {{ $booking->date }}</p>
                            <p><strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
                            @if ($booking->status == 'rejected')
                                <p><strong>Reason:</strong> {{ $booking->rejection_reason }}</p>
                            @endif
                            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
