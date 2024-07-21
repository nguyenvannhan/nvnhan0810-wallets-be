@extends('layouts.default')

@section('styles')
<style>
    #friend-detail {
        max-width: calc(100vw - 24px);
        width: 100%;
    }

    @media screen and (min-width: 500px) {
        #friend-detail {
            max-width: 476px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4 position-relative">
    <div id="friend-detail" class="position-fixed top-4 mb-4">
        <div class="card w-100 rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold fs-6 mb-0">{{ $friend->name }}</h5>

                    <a href="{{ route('friends.edit', $friend) }}" class="text-decoration-none">
                        Chỉnh sửa
                    </a>
                </div>

                <hr class="my-2" />

                @foreach($friend->borrowTransactions as $transaction)
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-6 fw-light">{{ $account->description }}</span>
                    <span class="fs-6 fw-bold {{ $account->amount > 0 ? 'text-success' : ($account->amount === 0 ? '' : 'text-danger') }}">
                        {{ $account->amount_currency }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
