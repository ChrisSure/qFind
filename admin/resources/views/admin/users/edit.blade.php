@extends('layouts.main')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="email" class="col-form-label">E-Mail</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="password" class="col-form-label">Password</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('password') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="role" class="col-form-label">Role</label>
            <select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role">
                @foreach ($rolesList as $value => $label)
                    <option value="{{ $value }}"{{ $value === old('role', $user->roles[0]) ? ' selected' : '' }}>{{ $label }}</option>
                @endforeach;
            </select>
            @if ($errors->has('role'))
                <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="role" class="col-form-label">Status</label>
            <select id="role" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                @foreach ($statusList as $value => $label)
                    <option value="{{ $value }}"{{ $value === old('status', $user->status) ? ' selected' : '' }}>{{ $label }}</option>
                @endforeach;
            </select>
            @if ($errors->has('status'))
                <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
@endsection
