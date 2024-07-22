@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div id="borrow-list" class="mb-4">
        <h3 class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-4">Mượn - Cho mượn</span>
            <a class="fs-5" href="{{ route('borrows.create') }}">Tạo mới</a>
        </h3>

        @php
            $total = $transactions->where('type', 'borrow')->reduce(function ($result, $transaction) {
                return $result + $transaction->amount;
            }, 0);
        @endphp

        <div class="mb-4 bg-white text-center text-danger py-4 fs-2 fw-bold">
            {{ '-' . number_format($total) . ' VNĐ' }}
        </div>

        <div class="list flex flex-column gap-2">
            @foreach($transactions->where('amount', '>', 0) as $transaction)
            <div class="bg-white">
                <a href="{{ route('borrows.edit', $transaction) }}" class="d-flex justify-content-between align-items-center p-3 text-decoration-none">
                    <div class="d-flex flex-column gap-2">
                        <span>{{ $transaction->type === 'borrow' ? 'Mượn: ' : 'Cho mượn: ' }} {{ $transaction->friend->name }}</span>
                        <span>{{ $transaction->description }}</span>
                    </div>
                    <span class="{{ $transaction->type === 'borrow' ? 'text-danger' : 'text-success' }}">{{ number_format($transaction->amount) . ' VNĐ' }}</span>
                </a>
            </div>
            @endforeach

            @foreach($transactions->where('amount', '<=', 0) as $transaction)
            <div class="bg-white">
                <div class="d-flex justify-content-between align-items-center p-3 text-decoration-none">
                    <div class="d-flex flex-column gap-2">
                        <span>{{ $transaction->type === 'borrow' ? 'Mượn: ' : 'Cho mượn: ' }} {{ $transaction->friend->name }}</span>
                        <span>{{ $transaction->description }}</span>
                    </div>
                    <span>{{ number_format($transaction->amount) . ' VNĐ' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
