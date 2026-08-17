@extends('admin.layout.app')

@section('content')
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>

    <h2>Welcome, Admin!</h2>
@endsection
