@extends('layouts.default')

@section('styles')
<style>
@media screen and (min-width: 500px) {
    #appbar {
        -webkit-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.1);
        box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.1);
    }
}

#content {
    margin-top: 3.5rem;
    margin-bottom: 1rem;
}

</style>
@endsection

@section('content')
<div id="appbar" class="position-fixed top-0 bg-white w-100">
    <h1 class="text-center py-2 fs-4 mb-0">Lịch sử giao dịch</h1>
</div>

<div id="content" class="container px-2">
    <div class="bg-white rounded-4 p-4 w-full">
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
</div>
@endsection

@section('scripts')
<script>
    const loadingEl = document.getElementById('transaction-loading');
    const transactionWrapper = document.getElementById('transaction-list-wrapper');

    let startDate, endDate = null;

    const loadTransactions = () => {
        document.getElementById('more-wrapper').classList.add('d-none');
        loadingEl.classList.add('d-block');
        loadingEl.classList.remove('d-none');

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
