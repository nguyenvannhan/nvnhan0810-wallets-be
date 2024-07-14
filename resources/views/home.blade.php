@extends('layouts.default')

@section('content')
    <form action="{{ route('logout') }}" method="POST">
        @csrf()
        <button type="submit">Logout</button>
    </form>
@endsection
