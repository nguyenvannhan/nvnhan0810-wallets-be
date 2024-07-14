@extends('layouts.default')

@section('content')
<h1>Transactions</h1>

@forelse($transactions as $transaction)
<table class="table table-striped table-bordered">
    <tr>
        <td>{{ $transaction->type }}</td>
        <td>{{ $transaction->amount }}</td>
        <td>{{ $transaction->description }}</td>
        <td>
            <div class="flex gap-3">
                <a href="{{ route('transactions.edit', $transaction->id) }}">Edit</a>

                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                    @csrf
                    @method("DELETE")

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </td>
    </tr>
</table>
@empty
<p>No transactions found</p>
@endforelse
@endsection
