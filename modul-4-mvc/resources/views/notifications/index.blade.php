@extends('layouts.adminlte-app')

@section('title', 'Notifications')

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
            @endif

            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="mb-3">
                @csrf
                <button class="btn btn-sm btn-success">Mark All as Read</button>
            </form>

            <div class="row">
                <div class="col-lg-12">
                    @foreach ($notifications as $note)
                        <div class="card card-outline @if (!$note->is_read) card-warning @else card-secondary @endif mb-2">
                            <div class="card-header">
                                <h5 class="m-0">{{ $note->title }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $note->message }}</p>
                                <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                                <div class="mt-2">
                                    <a href="{{ route('notifications.show', $note->id) }}" class="btn btn-info btn-sm">View</a>
                                    <form action="{{ route('notifications.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
