@extends('layouts.default')

@section('content')
<h1>Wallet List</h1>
<a href="{{ route('wallets.create') }}">Create Wallet</a>

<table>
    @foreach($wallets as $wallet)
    <tr>
        <td>{{ $wallet->name }}</td>
        <td>
            <a href="{{ route('wallets.edit', $wallet->id) }}">Chỉnh sửa</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
