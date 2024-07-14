@extends('layouts.default')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center gap-3 w-100">
    <img class="" src="{{ asset('images/logo.png') }}" alt="logo" />

    <h1 class="fw-bold">E-Wallets</h1>

    <a href="{{ route('login') }}" class="btn btn-primary ml-4">Login</a>
</div>
@endsection
