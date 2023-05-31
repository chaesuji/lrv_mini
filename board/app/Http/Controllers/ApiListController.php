<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $user = Boards::find($id);
        return response()->json($user, 200);
        // json() : json 형태로 변환
        // https://www.postman.com/downloads/
    }
    function postlist(Request $req) {
        // 유효성 체크 필요
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');

        return $arr; // 라라벨이 자동으로 json 형식으로 변환해줌
    }
}
