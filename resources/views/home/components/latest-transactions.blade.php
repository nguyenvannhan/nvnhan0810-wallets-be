<div class="text-center mb-5">
    <table class="table table-bordered table-striped">
        @foreach($latestTransactions as $transaction)
        <tr>
            <td>
                <p class="fw-lighter mb-2">{{ $transaction->description }}</p>
                <p class="mb-0">{{ $transaction->walletAccount->wallet->name . ' - ' . $transaction->walletAccount->name }}</p>
            </td>
            <td class="text-{{ $transaction->is_income  ? 'success' : 'danger' }}">
                {{ ($transaction->is_income ? '' : '-') . $transaction->amount_currency }}
            </td>
            <td>
                <a href="{{ route('transactions.edit', $transaction->id) }}">Edit</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>
