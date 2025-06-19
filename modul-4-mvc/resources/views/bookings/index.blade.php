@extends('layouts.adminlte-app')

@section('title', 'Booking List')

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
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @role('student')
                <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Make Booking</a>
            @endrole

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Booking List</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Group</th>
                                        <th>Room</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->group->name }}</td>
                                            <td>{{ $booking->room->name }}</td>
                                            <td>{{ $booking->date }}</td>
                                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                            <td>
                                                @if ($booking->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($booking->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm">View</a>
                                                @role('admin')
                                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning btn-sm">Review</a>
                                                @endrole
                                                @if (Auth::user()->hasRole('admin') || $booking->group->leader_id === Auth::id())
                                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this booking?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
