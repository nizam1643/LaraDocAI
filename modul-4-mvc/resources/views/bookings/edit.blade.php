@extends('layouts.adminlte-app')

@section('title', 'Review Booking')

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
            <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Review Booking</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Group:</strong> {{ $booking->group->name }}</p>
                        <p><strong>Room:</strong> {{ $booking->room->name }}</p>
                        <p><strong>Date:</strong> {{ $booking->date }}</p>
                        <p><strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Reason (if rejected)</label>
                            <textarea name="rejection_reason" class="form-control"></textarea>
                        </div>

                        <button class="btn btn-success">Submit</button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
