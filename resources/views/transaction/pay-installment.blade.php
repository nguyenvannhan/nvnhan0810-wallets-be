@extends('layouts.default')

@section('styles')
<style>
    .main-content {
        max-width: 600px;
        margin: auto;
    }
</style>
@endsection

@section('content')
<main class="container d-flex bg-white m-4 rounded-4 p-4 m-md-auto">
    <div class="main-content">
        <h1 class="text-center">Pay Transaction</h1>

        @if($errors->any())
        <div class="alert alert-danger mb-4 ps-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="transaction-form" action="{{ route('transactions.pay_installment') }}" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}" />

            <div class="mb-4">
                <label for="type" class="form-label">Wallet</label>

                <select id="wallet-select" class="form-select" name="wallet_id">
                    @foreach($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="type" class="form-label">Wallet Account</label>

                <select id="wallet-account-select" class="form-select mb-4" name="wallet_account_id">
                    @foreach($wallets->first()->walletAccounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name ?? $account->type_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const walletSelect = document.getElementById('wallet-select');
    const wallets = @json($wallets);
    const waleltAccountNames = @json($walletAccountTypes);

    walletSelect.addEventListener('change', (event) => {
        const walletId = event.target.value;

        const accounts = wallets.find((wallet) => parseInt(wallet.id) === parseInt(walletId)).wallet_accounts;

        const optionsHtml = accounts.map((account) => {
            return `<option value="${account.id}">${account?.name ?? waleltAccountNames[account.type]['name'] ?? waleltAccountNames['default']['name']}</option>`;
        });

        document.getElementById('wallet-account-select').innerHTML = optionsHtml;
    });
</script>
@endsection
