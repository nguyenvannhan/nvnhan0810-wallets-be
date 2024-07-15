@extends('layouts.default')

@section('styles')
<style>
    .avatar {
        width: 8rem;
        height: 8rem;
        border-radius: 100%;
    }
</style>
@endsection

@section('content')
<div class="container mx-4 my-auto bg-white rounded-3 p-4 d-flex flex-column gap-4 justify-content-center align-items-center m-md-auto">
    <img src="{{ $user->avatar }}" alt="avatar" class="avatar" />

    <div class="d-flex flex-column gap-2">
        <div class="d-flex flex-column gap-1">
            <label class="fw-bold">Name</label>
            <span class="ms-4">{{ $user->name }}</span>
        </div>
        <div class="d-flex flex-column gap-1">
            <label class="fw-bold">Email</label>
            <span class="ms-4">{{ $user->email }}</span>
        </div>
    </div>

    <div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf()
            <button type="submit" class="btn btn-outline-secondary">Logout</button>
        </form>
    </div>
</div>
@endsection
