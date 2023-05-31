<?php
/*
프로젝트명 : lrv_board
디렉토리 : Contrllers
파일명 : UserController.php
이력 : v001 0530 sj.chae new
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Session;
use App\Models\User;
// use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    function login(){
        return view('login');
    }

    function loginpost(Request $req){
        $req->validate([
            'email' => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))){
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
            // collect() : 여러 작업을 연속적으로 수행할 수 있도록 체이닝을 지원하는 메서드 제공
            // 배열의 대체제로 사용가능(array 타입으로 타입힌트된 경우가 아니라면 배열이 사용되는 모든 경우에 사용가능함)
            // php의 배열에 비해 laravel의 컬렉션이 사용할 수 있는 메서드 수가 많음
        }

        // 유저 인증 작업
        Auth::login($user);
        if(Auth::check()){
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            // * session($user->only('id')) : 배열 형식
            return redirect()->intended(route('boards.index'));
        }else{
            $error = '유저 인증 작업 에러';
            return redirect()->back()->with('error', $error);
        }
    }

    function registration(){
        return view('registration');
    }

    function registrationpost(Request $req){
        // 유효성 체크
        $req->validate([
            // ? regex: -> 정규식 체크
            'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email' => 'required|email|max:100'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            // ? required_unless: -> 입력한 값들이 일치하지 않으면 에러
            // ? same: -> 필드의 값이 주어진 field의 값과 일치해야함
            // 'password' => 'same:passwordchk' : password와 passwordchk가 일치하는지 확인
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        // user(->model)에 insert
        $user = User::create($data);
        if(!$user){
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 시도해 주세요.';

            return redirect()->route('users.registration')->with('error', $error);
            // * collect() : 콜렉션 객체로 변환
        }
        // 회원가입 완료, 로그인 페이지로 이동
        return redirect()
        ->route('users.login')->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해주십시오.');
    }

    // 로그아웃
    function logout(){
        Session::flush(); // 세션 파기 
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
        // session()->flash();
    }

    function useredit(){
        // $id = session('id');
        // $data = User::find($id);
        $data = User::find(Auth::User()->id);

        return view('useredit')->with('data', $data);
    }

    function usereditpost(Request $req){
        // if($req->name && $req->password){
        //     $req->validate([
        //         'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //         ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        // }else if($req->name){
        //     $req->validate([
        //         'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //     ]);
        // }else if($req->password){
        //     $req->validate([
        //         'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        // }
        //     $id = session('id');
        //     $data = User::find($id);
        //     $data->name = $req->name;
        //     $data->password = $req->password;
        //     $data->password = Hash::make($req->password);
        //     $data->save();

        //     if(!$data){
        //         $error = '시스템 에러가 발생하여, 회원 수정에 실패했습니다.<br>잠시 후에 다시 시도해 주세요.';
        //         return redirect()->route('users.useredit')->with('error', $error);
        //     }

        // $arrkey = []; // 수정할 항목 배열에 담는 변수
        // $data = User::find(Auth::User()->id); // 기존 데이터 가져오기

         // 기존 비밀번호 비교
        // if(!Hash::check($req->bpassword, $data->bpassword)){
        //     return redirect()->back()->with('error', '기존 비밀번호를 확인해주세요');
        // }

         // 수정할 항목을 배열에 담는 처리
        // if($req->name !== $data->name){
        //     $arrkey[] = 'name';
        // }
        // if(isset($req->password)){
        //     $arrkey[] = 'password';
        // }
        
         // 유효성 체크를 하는 모든 항목 리스트
        // $chklist = [
        //     'name' => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //     ,'bpassword' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        //     ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        // ];

         // 유효성 체크할 항목 셋팅하는 처리
        // $arrchk['bpassword'] = $chklist['bpassword'];
        // foreach ($arrkey as $val) {
        //     $arrchk[$val] = $chklist[$val];
             // $arrchk -> 수정할 할목을 배열에 담는 처리에서의 $arrchk
        // }

        // $req->validate($arrchk); // 유효성 체크

         // 수정할 데이터 셋팅
        // foreach ($arrkey as $val) {
        //     if($val === 'password'){
        //         $data->$val = Hash::make($req->$val);
        //         continue;
        //     }
        //     $data->$val = $req->$val;
        // }
        // $data->save(); // update

        $arrKey = []; // 수정할 항목을 배열에 담는 변수

        $baseUser = User::find(Auth::User()->id); // 기존 데이터 획득

        // 기존 패스워드 체크
        if(!Hash::check($req->bpassword, $baseUser->password)) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요.');
        }

        // 수정할 항목을 배열에 담는 처리
        if($req->name !== $baseUser->name) {
            $arrKey[] = 'name';
        }
        if($req->email !== $baseUser->email) {
            $arrKey[] = 'email';
        }
        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        // 유효성체크를 하는 모든 항목 리스트
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'bpassword'=> 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];

        // 유효성 체크할 항목 셋팅하는 처리
        $arrchk['bpassword'] = $chkList['bpassword'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        //유효성 체크
        $req->validate($arrchk);

        // 수정할 데이터 셋팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseUser->$val = Hash::make($req->$val);
                continue;
            }
            $baseUser->$val = $req->$val;
        }
        $baseUser->save(); // update

        return redirect()->route('boards.index');
    }

    // 회원탈퇴
    function withdraw(){
        $id = session('id');
        $result = User::destroy($id);
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }
}
