@extends('layouts.errors')

@section('content')
    <h1 class="text-center text-danger">Error 401 {{ $exception->getMessage() }}</h1>
@endsection
