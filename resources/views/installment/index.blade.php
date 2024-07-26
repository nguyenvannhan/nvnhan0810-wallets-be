@extends('layouts.default')

@section('content')
<div class="container py-4">
    {{-- @php dd(session('create-transaction-success')); @endphp --}}
    @if(session('create-transaction-success'))
        <div class="text-success text-center bg-white py-2 px-4 mb-4">
            Tạo giao dịch thanh toán thành công
        </div>
    @endif

    @if($errors->any())
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    <div id="borrow-list" class="mb-4">
        <h3 class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-4">Trả góp</span>
            <a class="fs-5" href="{{ route('installments.create') }}">Tạo mới</a>
        </h3>

        @php
            $total = $installments->reduce(function ($result, $installment) {
                return $result + ($installment->monthly_amount * $installment->remain_months);
            }, 0);
        @endphp

        <div class="mb-4 bg-white text-center text-danger py-4 fs-2 fw-bold">
            {{ '-' . number_format($total) . ' VNĐ' }}
        </div>

        <div class="list flex flex-column gap-2">
            @foreach($installments as $transaction)
            <div class="bg-white">
                <div class="d-flex justify-content-between align-items-center p-3 text-decoration-none">
                    <div class="d-flex flex-column gap-2">
                        <span>{{ $transaction->name }}</span>
                        <span>{{ $transaction->next_paid_date }} *** {{ $transaction->remain_months }}</span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <span class="text-danger">{{ $transaction->monthly_amount_currency }}</span>
                        <form action="{{ route('installements.transactions.create', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link">Tạo thanh toán</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
