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

    // 오픈 api 데이터 불러오기
    // https://anko3899.tistory.com/476
    // https://domdom.tistory.com/233
    // json_encode / json_decode 
    // http://www.tcpschool.com/json/json_use_php
    public function index() {
        // Http::get -> 메소드 요청 방식 
        $foodinfo = Http::get('https://apis.data.go.kr/1471000/FoodNtrIrdntInfoService1/getFoodNtrItdntList1?serviceKey=bmFaWcRSgAvoTtX4icXz1GOdbD7o%2FMS%2BrX8nxsazgSgkLHja%2Bm7UT3I2BGnyAxapTRLBhq1IUH%2B%2BaykFTHQevg%3D%3D');

        // simplexml_load_string() : 문자열(string)으로부터 xml 데이터를 읽는데에 사용(=xml 문자열을 객체로 해석 후 반환)
        // https://www.php.net/manual/en/function.simplexml-load-string.php
        $xml = simplexml_load_string($foodinfo);
        // json_encode() : 전달받은 값을 json 형식의 문자열로 변환하여 반환
        $json = json_encode($xml);

        // json_decode() : 전달받은 json 형식의 값을 php 변수로 변환하여 반환
        $array = json_decode($json, TRUE);

        dd($array);
        return view('index');
    }

    // 공공데이터 api 사이트에서 제공하는 샘플 코드
    // public function index1(){
    //     $ch = curl_init();
    //     $url = 'http://apis.data.go.kr/1471000/FoodNtrIrdntInfoService1/serviceKey=bmFaWcRSgAvoTtX4icXz1GOdbD7o%2FMS%2BrX8nxsazgSgkLHja%2Bm7UT3I2BGnyAxapTRLBhq1IUH%2B%2BaykFTHQevg%3D%3D'; /*URL*/
    //     $queryParams = '?' . urlencode('serviceKey') . '=서비스키'; /*Service Key*/
    //     $queryParams .= '&' . urlencode('desc_kor') . '=' . urlencode('바나나칩'); /**/
    //     $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /**/
    //     $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('3'); /**/
    //     $queryParams .= '&' . urlencode('bgn_year') . '=' . urlencode('2017'); /**/
    //     $queryParams .= '&' . urlencode('animal_plant') . '=' . urlencode('(유)돌코리아'); /**/
    //     $queryParams .= '&' . urlencode('type') . '=' . urlencode('json'); /**/

    //     curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     // var_dump($response);
    //     return view('index');
    // }
}
