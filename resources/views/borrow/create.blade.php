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
<main class="container d-flex m-4 bg-white rounded-4 py-4 mx-md-auto">
    <div class="main-content">
        <h1 class="text-center">Mượn - Cho mượn</h1>

        @if($errors->any())
        <div class="alert alert-danger mb-4 ps-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="debt-form" class="w-100" action="{{ route('borrows.store') }}" method="POST">
            @csrf()

            <div class="mb-4">
                <label for="amount" class="form-label">Amount</label>
                <input id="amount" name="amount" type="number" class="form-control" />
            </div>

            <div class="mb-4">
                <label for="type" class="form-label">Type</label>

                <div>
                    @foreach($typeList as $key => $type)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="type" type="radio" id="{{ $key }}-radio" value="{{ $key }}">
                        <label class="form-check-label" for="{{ $key }}-radio">{{ ucwords($type) }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="wallet-select" class="form-label">Ví</label>

                <select id="wallet-select" class="form-select mb-4" name="wallet_id">
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>

                <select id="wallet-account-select" class="form-select mb-4" name="wallet_account_id">
                    @foreach($wallets->first()->walletAccounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name ?? $account->type_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="transaction-date" class="form-label">Ngày</label>
                <input class="form-control" id="transaction-date" name="transaction_date" />
            </div>

            <div class="mb-4">
                <label for="friend-select" class="form-label">Bạn bè</label>
                <select id="friend-select" class="form-select mb-4" name="friend_id">
                    @foreach($friends as $friend)
                    <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Create</button>
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
