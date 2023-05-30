<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    @include('layout.errorsvaildate')

    <form action="{{route('boards.store')}}" method="post">
    @csrf
        <label for="title">제목</label>
        <input type="text" name="title" id="title" value="{{old('title')}}">
        {{-- old() : 세션에 저장된 이전 입력값을 조회(자동으로 name이 old() 안에 들어가서 처리) --}}
        <br>
        <label for="content">내용</label>
        <textarea name="content" id="cotent" cols="30" rows="5">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>