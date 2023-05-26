<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        글번호 : {{$data->id}}
        <br>
        제목 : {{$data->title}}
        <br>
        내용 : {{$data->content}}
        <br>
        등록일 : {{$data->created_at}}
        <br>
        수정일 : {{$data->updated_at}}
        <br>
        조회수 : {{$data->hits}}
        <br>
        <button type="button" onclick="location.href='{{route('boards.index')}}'">list</button>
        <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">update</button>
    </div>
</body>
</html>