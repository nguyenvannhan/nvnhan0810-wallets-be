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
        <h1 class="text-center">Chỉnh sửa bạn</h1>

        @if($errors->any())
        <div class="alert alert-danger mb-4 ps-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="wallet-form" class="w-100" action="{{ route('friends.update', $friend) }}" method="POST">
            @csrf()
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="form-label">Tên</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ $friend->name }}" />
            </div>

            <button type="submit" class="btn btn-primary w-100">Update</button>
        </form>
    </div>
</main>
@endsection
