@extends('layouts.default')

@section('content')
<div class="container">
    <div class="text-center">
        <form action="{{ route('logout') }}" method="POST">
            @csrf()
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
@endsection
