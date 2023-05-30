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
use App\Models\User;

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
            $errors[] = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('errors', collect($errors));
        }

        // 유저 인증 작업
        Auth::login($user);
        if(Auth::check()){
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        }else{
            $errors[] = '유저 인증 작업 에러';
            return redirect()->back()->with('errors', collect($errors));
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
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        // user(->model)에 insert
        $user = User::create($data);
        if(!$user){
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 시도해 주세요.';

            return redirect()->route('users.registration')->with('errors', collect($errors));
            // * collect() : 콜렉션 객체로 변환
        }
        // 회원가입 완료, 로그인 페이지로 이동
        return redirect()
        ->route('users.login')->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해주십시오.');
    }
}
