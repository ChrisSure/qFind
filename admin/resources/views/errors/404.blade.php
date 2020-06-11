@extends('layouts.errors')

@section('content')
    <h1 class="text-center text-danger">Error 404 {{ $exception->getMessage() }}</h1>
@endsection
