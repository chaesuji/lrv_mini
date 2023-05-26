<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="post">
    @csrf
    @method('put')
        <label for="title">제목</label>
        <input type="text" name="title" id="title" value="{{$data->title}}">
        <br>
        <label for="content">내용</label>
        <textarea name="content" id="cotent" cols="30" rows="5">{{$data->content}}</textarea>
        <br>
        <button type="button" onclick="location.href='{{route('boards.show', ['board' => $data->id])}}'">취소</button>
        <button type="submit">수정</button>
    </form>
    <form action="{{route('boards.destroy', ['board' => $data->id])}}" method="post">
        @csrf
        @method('delete')
        <button type="submit">삭제</button>
    </form>
</body>
</html>