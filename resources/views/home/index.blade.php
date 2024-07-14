@extends('layouts.default')

@section('content')
<div class="container py-4">
    @include('home.components.wallet-list')

    @include('home.components.latest-transactions')
</div>
@endsection

@section('scripts')
    @vite(['resources/js/home.js'])
@endsection
