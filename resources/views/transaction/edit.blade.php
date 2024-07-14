@extends('layouts.default')

@section('content')
<form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="flex mb-4">
        <label for="type">Amount</label>
        <input type="number" name="amount" value="{{ $transaction->amount }}" />
    </div>
    <div class="flex mb-4">
        <label for="type">Type</label>
        <input type="text" name="type" value="{{ $transaction->type }}" />
    </div>

    <div class="flex mb-4">
        <label for="type">Wallet Account</label>
        <input type="number" name="wallet_account_id" value="{{ $transaction->wallet_account_id }}" />
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>
@endsection
