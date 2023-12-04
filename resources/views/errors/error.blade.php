@extends('layout')

@section('content')
    <div class="container">
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ $message }}
        </div>
    </div>
@endsection
