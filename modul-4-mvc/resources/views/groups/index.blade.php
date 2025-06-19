@extends('layouts.adminlte-app')

@section('title', 'My Groups')

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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3">
                <a href="{{ route('groups.create') }}" class="btn btn-primary">Create New Group</a>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @foreach($groups as $group)
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">{{ $group->name }}</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">Leader: {{ $group->leader->name }}</h6>
                                <p class="card-text">Members: {{ $group->users->pluck('name')->join(', ') }}</p>
                                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this group?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
