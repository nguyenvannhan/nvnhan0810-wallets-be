@extends('layouts.default')

@section('content')
<h1>Create Wallet</h1>

<form action="{{ route('wallets.update', $wallet->id) }}" method="POST">
    @method('PATCH')
    @csrf()

    <div class="flex gap-3 mb-4">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" class="text-black" value="{{ $wallet->name }}" />
    </div>

    <div id="type-groups">
        <div id="type-group-0">
            <div class="flex mb-4">
                <label for="type">Types</label>
                <select id="type" name="accounts[0]['type']">
                    @foreach($accounts as $key => $type)
                    <option value="{{ $key }}" {{ $walletAccounts->contains('type', $key) ? 'selected' : '' }}>{{ $type['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex mb-4">
                <label for="type">Balance</label>
                <input type="number" name="accounts[0]['balance']" value="{{ $walletAccounts->first()?->ballance ?? 0 }}" />
            </div>
        </div>
    </div>

    <button type="submit">Update</button>
</form>
@endsection
