@extends('layouts.main')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <h1>Users</h1>
    <a type="submit" href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Create</a>
    <div class="card mb-3">
        <div class="card-header"><b>Filter</b></div>
        <div class="card-body">
            <form action="?" method="GET">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email</label>
                            <input id="email" class="form-control" name="email" value="{{ request('email') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option value=""></option>
                                @foreach ($statusList as $value => $label)
                                    <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>{{ $label }}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="role" class="col-form-label">Role</label>
                            <select id="role" class="form-control" name="role">
                                <option value=""></option>
                                @foreach ($rolesList as $value => $label)
                                    <option value="{{ $value }}"{{ $value === request('role') ? ' selected' : '' }}>{{ $label }}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group line-button-left">
                            <label class="col-form-label">&nbsp;</label><br />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label><br />
                            <a href="{{ route('admin.users.index') }}" type="submit" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!(empty($users)))
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Status</th>
            <th>Role</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user->id) }}">{{ $user->email }}</a></td>
                <td><span class="badge badge-primary">{{ $user->status }}</span></td>
                <td><span class="badge badge-success">{{ $user->roles[0] }}</span></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('admin.include.pagination.pagination', ['paginationArray' => $paginationArray])

    @else
        <h4>Not records ...</h4>
    @endif

@endsection
