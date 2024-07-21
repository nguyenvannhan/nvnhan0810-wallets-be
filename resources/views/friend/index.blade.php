@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div id="wallet-list" class="mb-4">
        <h3 class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-4">Danh sách Bạn</span>
            <a class="fs-5" href="{{ route('friends.create') }}">Tạo mới</a>
        </h3>

        <div class="row">
            @foreach($friendList as $friend)
            <div class="col-12 mb-2">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold fs-6">{{ $friend->name }}</h5>
                            <a href="{{ route('wallets.show', $friend->id) }}">
                                Chi tiết
                            </a>
                            @if($friend->borrow_transactions_count === 0)
                                <form class="d-inline" action="{{ route('friends.destroy', $friend) }}" method="POST">
                                    @csrf()
                                    @method('DELETE')
                                    <button class="text-danger btn-link btn" href="{{ route('wallets.show', $friend->id) }}">
                                        Xoá
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
