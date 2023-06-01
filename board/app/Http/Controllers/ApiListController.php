<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

class ApiListController extends Controller
{
    function getlist($id) {
        // get
        $user = Boards::find($id);
        return response()->json($user, 200);
        // json() : json 형태로 변환
        // https://www.postman.com/downloads/
    }
    function postlist(Request $req) {
        // post 
        // 유효성 체크 필요
        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');

        return $arr; // 라라벨이 자동으로 json 형식으로 변환해줌
    }

    function putlist(Request $req, $id) {
        // 업데이트
        $arrData = [
            'errorcode' => '0'
            ,'msg' => ''
            ,'errmsg' => []
        ];
        
        // 유효성 체크
        $arr = ['id' => $id];
        $req->request->add($arr);
        
        $validator = Validator::make($req->only('id', 'title', 'content'), [
            'id' => 'required|integer'
            ,'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        if($validator->fails()){
            $arr1['errorcode'] = '1';
            $arr1['msg'] = 'validate fail';
            $arr1['arrmsg'] = $validator->errors()->all();
            $arr1['id'] = $id;
            
            return $arr1;
        }else if(Boards::find($id) === null){
            $arr['errorcode'] = '2';
            $arr['msg'] = 'fail';
            $arr['data'] = $id;
        }else{
            $boards = Boards::find($id);
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();

            $arr['errorcode'] = '0';
            $arr['msg'] = 'success';
            $arr['data'] = $boards->only('id', 'title');
        }
        return $arr;
    }

    function deletelist($id) {
        // 삭제
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:boards'
        ]);
        if($validator->fails()){
            $arr['errorcode'] = '2';
            $arr['msg'] = 'validate fail';
        }else if(Boards::find($id) === null){
            $arr['errorcode'] = '1';
            $arr['msg'] = 'id find fail';
        }else{
            Boards::destroy($id);
    
            $arr['errorcode'] = '0';
            $arr['msg'] = 'success';
        }
        return $arr;
    }
}
