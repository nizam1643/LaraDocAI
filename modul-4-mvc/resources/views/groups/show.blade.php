@extends('layouts.adminlte-app')

@section('title', 'Group Details')

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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Group: {{ $group->name }}</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <h6 class="card-title">Members:</h6>
                            <ul>
                                @foreach ($members as $user)
                                    <li>
                                        {{ $user->name }}
                                        @if ($group->leader_id === Auth::id() && $user->id !== Auth::id())
                                            <form action="{{ route('groups.removeMember', [$group->id, $user->id]) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            @if ($group->leader_id === Auth::id())
                                <hr>
                                <form action="{{ route('groups.addMember', $group->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Add Member</label>
                                        <select name="user_id" class="form-control" required>
                                            @foreach ($allUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </form>
                            @endif

                            <a href="{{ route('groups.index') }}" class="btn btn-secondary mt-3">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
