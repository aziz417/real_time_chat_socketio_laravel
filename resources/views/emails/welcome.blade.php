@extends('layouts.app')

@section('content')
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for registering with our chat application by {{ $user->provider }}.</p>
@endsection
