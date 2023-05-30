@extends('layout.layout')

@section('title', 'login')

@section('contents')
@include('layout.errorsvaildate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="email">name : </label>
        <input type="text" id="name" name="name">
        <br>
        <label for="email">email : </label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">password : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">password check : </label>
        <input type="password" id="passwordchk" name="passwordchk">
        <br>
        <button type="submit">Regist</button>
        <button type="button" onclick="location.href='{{route('users.login')}}'">Cancel</button>
    </form>
@endsection