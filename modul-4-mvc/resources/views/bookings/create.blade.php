@extends('layouts.adminlte-app')

@section('title', 'Make Booking')

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
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Booking Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Group</label>
                            <select name="group_id" class="form-control" required>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <select name="room_id" class="form-control" required>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->location }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <button class="btn btn-success">Submit Booking</button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
