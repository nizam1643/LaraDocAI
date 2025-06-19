@extends('layouts.adminlte-app')

@section('title', 'Create Room')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title') ADMIN</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <form action="{{ isset($room) ? route('rooms.update', $room->id) : route('rooms.store') }}" method="POST">
                @csrf
                @if (isset($room))
                    @method('PUT')
                @endif
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">{{ isset($room) ? 'Edit Room' : 'Create Room' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" value="{{ old('location', $room->location ?? '') }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Capacity</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $room->capacity ?? '') }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control">{{ old('description', $room->description ?? '') }}</textarea>
                        </div>
                        <button class="btn btn-success">{{ isset($room) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
