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
        <h1 class="text-center">Tạo trả góp</h1>

        @if($errors->any())
        <div class="alert alert-danger mb-4 ps-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="debt-form" class="w-100" action="{{ route('installments.store') }}" method="POST">
            @csrf()

            <div class="mb-4">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" type="text" class="form-control" />
            </div>

            <div class="mb-4">
                <label for="monthly_amount" class="form-label">Số tiền hàng tháng</label>
                <input id="monthly_amount" name="monthly_amount" type="number" class="form-control" />
            </div>

            <div class="mb-4">
                <label for="wallet-select" class="form-label">Ví</label>

                <select id="wallet-select" class="form-select mb-4" name="wallet_id">
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>

                <select id="wallet-account-select" class="form-select mb-4" name="wallet_account_id">
                    <option value=""></option>
                    @foreach($wallets->first()->walletAccounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="start_paid_date" class="form-label">Ngày bắt đầu</label>
                <input class="form-control" id="start_paid_date" name="start_paid_date" />
            </div>

            <div class="mb-4">
                <label for="total_months" class="form-label">Tổng số tháng</label>
                <input id="total_months" name="total_months" type="number" class="form-control" />
            </div>

            <div class="mb-4">
                <label for="remain_months" class="form-label">Số tháng còn lại</label>
                <input id="remain_months" name="remain_months" type="number" class="form-control" />
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

        const optionsHtml = accounts.reduce((result, account) => {
            return result + `<option value="${account.id}">${waleltAccountNames[account.type]['name'] ?? waleltAccountNames['default']['name']}</option>`;
        }, '<option value=""></option>');

        document.getElementById('wallet-account-select').innerHTML = optionsHtml;
    });
</script>
@endsection
