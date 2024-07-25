@extends('layouts.default')

@section('content')
<div class="container py-4">
    @include('home.components.wallet-list')

    @if($totalInstallment > 0)
        <div class="mb-4">
            <h2 class="text-danger text-center fw-bold">-{{ number_format($totalInstallment) }} VND</h2>
        </div>
    @endif

    @if($statements->isNotEmpty())
        @include('home.components.statements')
    @endif

    @include('home.components.latest-transactions')
</div>
@endsection

@section('scripts')
    @vite(['resources/js/home.js'])
@endsection
