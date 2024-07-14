@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf()
            <button type="submit" class="btn btn-primary btn-sm">Logout</button>
        </form>
    </div>

    <div class="text-center">
        <a href="{{ route('transactions.create') }}" class="btn btn-success w-100 my-5">Tạo giao dịch</a>
    </div>

    <div id="wallet-list">
        <h3 class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-4">Danh sách ví</span>
            <a class="fs-5" href="{{ route('wallets.create') }}">Tạo mới</a>
        </h3>

        <div class="row">
            @foreach($wallets as $wallet)
            <div class="col-12 col-md-6 col-lg-4 mb-2">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold fs-6">{{ $wallet->name }}</h5>
                            <a href="{{ route('wallets.edit', $wallet->id) }}">
                                <i class="fa-solid fa-pen fs-6"></i>
                            </a>
                        </div>

                        <hr />

                        @foreach($wallet->walletAccounts as $account)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 fw-light">{{ $account->name }}</span>
                            <span class="fs-6 fw-bold {{ $account->balance > 0 ? 'text-success' : ($account->balance === 0 ? '' : 'text-danger') }}">
                                {{ $account->balance_currency }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
