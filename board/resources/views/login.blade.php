@extends('layout.layout')

@section('title', 'login')

@section('contents')
@include('layout.errorsvaildate')
<div>{{isset($success) ? $success : ""}}</div>
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">email : </label>
        <input type="text" id="email" name="email">
        <label for="password">password : </label>
        <input type="password" id="password" name="password">
        <br>
        <button type="submit">Login</button>
        <button type="button" onclick="location.href='{{route('users.registration')}}'">Registration</button>
    </form>
@endsection