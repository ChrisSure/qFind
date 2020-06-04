@extends('layouts.main')

@section('content')
    <h1>User</h1>

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

    @else
        <h4>Not record ...</h4>
    @endif

@endsection
