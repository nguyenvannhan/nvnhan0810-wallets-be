@extends('layouts.default')

@section('styles')
<style>
    #wallet-detail {
        max-width: calc(100vw - 24px);
        width: 100%;
    }

    @media screen and (min-width: 500px) {
        #wallet-detail {
            max-width: 476px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4 position-relative">
    <div id="wallet-detail" class="position-fixed top-4 mb-4">
        <div class="card w-100 rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold fs-6 mb-0">{{ $wallet->name }}</h5>

                    <a href="{{ route('wallets.edit', $wallet) }}" class="text-decoration-none">
                        Cân đối Ví
                    </a>
                </div>

                <hr class="my-2" />

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

    <div id="transaction-list" class="bg-white rounded-3 px-2 py-3 overflow-auto">
        <div id="transaction-list-wrapper">
        </div>
        <div id="more-wrapper" class="text-center d-none">
            <button id="btn-more" class="btn btn-outline-primary">Load thêm</button>
        </div>
        <div id="transaction-loading" class="text-center d-block">
            <span class="fa-3x">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const walletId = parseInt("{{$wallet->id}}");
    let endDate = null
    let startDate = null;

    const walleEl = document.getElementById('wallet-detail');
    const footerEl = document.getElementsByTagName('footer')[0];
    const transactionEl = document.getElementById('transaction-list');
    const loadingEl = document.getElementById('transaction-loading');
    const transactionWrapper = document.getElementById('transaction-list-wrapper');

    transactionEl.style.height = `calc(100dvh - ${walleEl.offsetHeight + footerEl.offsetHeight + 55}px)`;
    transactionEl.style.marginTop = `${walleEl.offsetHeight + 15}px`;

    const loadTransactions = () => {
        document.getElementById('more-wrapper').classList.add('d-none');
        loadingEl.classList.add('d-block');
        loadingEl.classList.remove('d-none')

        if (endDate) {
            endDate.setDate(endDate.getDate() - 7);
        } else {
            endDate = new Date();
        }

        if (startDate) {
            startDate.setDate(startDate.getDate() - 7);
        } else {
            startDate = new Date();
            startDate.setDate(startDate.getDate() - 7);
        }

        fetch('/transactions/load?' + new URLSearchParams({
                wallet_id: walletId,
                start_date: startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate(),
                end_date: endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate(),
            }).toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(async (res) => {
                const data = await res.json();

                transactionWrapper.insertAdjacentHTML('beforeend', data.transaction);
                loadingEl.classList.add('d-none');
                loadingEl.classList.remove('d-block')

                data.has_more ?
                    document.getElementById('more-wrapper').classList.remove('d-none') :
                    document.getElementById('more-wrapper').classList.add('d-none');
            })
            .catch((e) => {
                console.log(e);
                document.getElementById('more-wrapper').classList.add('d-none');
                loadingEl.classList.add('d-none');
                loadingEl.classList.remove('d-block')
            });
    }

    loadTransactions();

    document.addEventListener('click', (e) => {
        if (e.target.id === 'btn-more') {
            loadTransactions();
        }
    });
</script>
@endsection
