<h2>header</h2>
<br>
{{-- 로그인 한 상태의 유저 / 인증 O --}}
@auth
    <div><a href="{{route('users.logout')}}">logout</a></div>
    <div><a href="{{route('users.useredit')}}">mypage</a></div>
@endauth
{{-- 로그인을 하지 않은 상태의 유저 / 인증 X --}}
@guest
    <div><a href="{{route('users.login')}}">login</a></div>
@endguest

<hr>
<br>