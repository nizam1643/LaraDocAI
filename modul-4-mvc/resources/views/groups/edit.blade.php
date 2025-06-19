@extends('layouts.adminlte-app')

@section('title', 'Edit Group')

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
                    <form action="{{ route('groups.update', $group->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Edit Group</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Group Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                                </div>
                                <button class="btn btn-success">Update</button>
                                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
