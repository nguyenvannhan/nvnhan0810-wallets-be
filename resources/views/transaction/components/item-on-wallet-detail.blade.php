@php
$currentDate = $transactions->first()?->created_at;
@endphp
<div class="d-flex gap-3 flex-column">
    @foreach($transactions as $transaction)

    <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-truncate fw-light">{{ $transaction->walletAccount->wallet->name }} - {{ $transaction->walletAccount->name }}</span>
            <span class="text-nowrap ps-2">{{ $transaction->created_at->format("d/m/Y") }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span title="{{ $transaction->description }}" class="text-truncate">{{ $transaction->description }}</span>
            <a href="{{ route('transactions.edit', $transaction) }}" class="text-decoration-none">
                <span class="fw-bold text-nowrap ps-2 {{ $transaction->is_income ? 'text-success' : 'text-danger' }}">{{ $transaction->is_income ? '' : '-' }} {{ $transaction->amount_currency }}</span>
            </a>
        </div>

        @if(!$transaction->paid_status)
        <a href="{{ route('transactions.pay_installment_form', $transaction) }}" type="submit" class="btn btn-danger w-100">Chưa Thanh toán</a>
        @endif
    </div>

    @if(!$currentDate->isBetween($transaction->created_at->startOfDay(), $transaction->created_at->endOfDay()))
    @php $currentDate = $transaction->created_at; @endphp

    <div class="my-2 border-top"></div>
    @endif
    @endforeach
</div>

<div class="my-2 border-top"></div>
