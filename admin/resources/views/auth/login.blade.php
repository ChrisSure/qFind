@extends('layouts.auth')

@section('content')
    <section class="login">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="loginEmail">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="loginEmail">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="loginPassword">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="loginRemember">
                <label class="form-check-label" for="loginRemember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
@endsection
