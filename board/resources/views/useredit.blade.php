@extends('layout.layout')

@section('title', 'Mypage')

@section('contents')
@include('layout.errorsvaildate')
<div>{!!session()->has('success') ? session('success') : ""!!}</div>
    <form action="{{route('users.useredit.post')}}" method="post">
        @csrf
        {{$data->name}}<br>
        <label for="name">name : </label>
        <input type="text" id="name" name="name" value="{{$data->name}}">
        <br>
        <label for="email">email : </label>
        <input type="text" id="email" name="email" value="{{$data->email}}" readonly>
        <br>
        <label for="bpassword">Before password : </label>
        <input type="password" id="bpassword" name="bpassword">
        <br>
        <label for="password">After password : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">password check : </label>
        <input type="password" id="passwordchk" name="passwordchk">
        <br>
        <span>가입일 : {{$data->created_at}}</span>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('users.withdraw')}}'">탈퇴</button>
    </form>
@endsection