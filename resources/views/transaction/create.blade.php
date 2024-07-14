@extends('layouts.default')

@section('content')
<form action="{{ route('transactions.store') }}" method="POST">
    @csrf

    <div class="flex mb-4">
        <label for="type">Amount</label>
        <input type="number" name="amount" value="0" />
    </div>
    <div class="flex mb-4">
        <label for="type">Type</label>
        <input type="text" name="type" value="income" />
    </div>

    <div class="flex mb-4">
        <label for="type">Wallet Account</label>
        <input type="number" name="wallet_account_id" value="1" />
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>
@endsection
