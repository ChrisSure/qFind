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

    @if(!(empty($user)))
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th><td>{{ $user->id }}</td>
            </tr>

            <tr>
                <th>Email</th><td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge badge-primary">{{ $user->status }}</span>
                </td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    <span class="badge badge-success">{{ $user->roles[0] }}</span>
                </td>
            </tr>
            </tbody>
        </table>
        @if ($user->roles[0] === 'ROLE_ADMIN' || $user->roles[0] === 'ROLE_SUPER_ADMIN')
            <a type="submit" href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary mb-3">Edit</a>
        @endif
        @if ($user->roles[0] === 'ROLE_USER' && ($user->status === 'new' || $user->status === 'blocked'))
            <a type="submit" href="{{ route('admin.users.activate', $user->id) }}" class="btn btn-primary mb-3">Activate</a>
        @endif
        @if ($user->roles[0] === 'ROLE_USER' && $user->status === 'active')
            <a type="submit" href="{{ route('admin.users.block', $user->id) }}" class="btn btn-primary mb-3">Block</a>
        @endif
        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger mb-3 actionRemove">Delete</button>
        </form>
    @else
        <h4>Not record ...</h4>
    @endif

    @if(!(empty($user->social)))
        <hr/>
        <p>
            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Show social networks
            </a>
        </p>
        <div class="collapse" id="collapseExample">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>App id</th>
                    <th>Provider</th>
                    <th>Name</th>
                    <th>Image</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($user->social as $social)
                    <tr>
                        <td>{{ $social->appId }}</td>
                        <td>{{ $social->provider }}</td>
                        <td>{{ $social->name }}</td>
                        <td><img src="{{ $social->image }}" style="height: 70px;"/></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
