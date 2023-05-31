<?php
/****************************
 * 프로젝트명 : lrv_board
 * 디렉토리 : Contrllers
 * 파일명 : BoardsController.php
 * 이력 : v001 0526 sj.chae new
 *        v002 0530 sj.chae 유효성 체크 추가 update
 *        v003 0531 sj.chae auth check 추가 index
*****************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator; // v002 add

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // v003 auth check
        // * 로그인을 하지 않은 유저의 접근 막기
        // $this->logincheck();
        if(auth()->guest())
        return redirect()->route('users.login');
        // v003 end
        $result = Boards::select('id','title','hits','created_at','updated_at')->orderBy('hits', 'desc')->get();
        return view('List')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // v002 add start
        // 게시판 유효성 체크
        $req->validate([ // validate에서 에러 발생시 view에 errors 변수
            // 필수 입력 값, 최소/최대 입력 값 지정
            // * | : or, between(min, max), max:num, min:num
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // v002 add end
        // insert
        $boards = new Boards([
        // ? new Boards : 새로운 엘로퀀트 객체 생성, db에서 가져오는 값이 아님(insert, 입력)
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save(); // save() : created_at, updated_at 자동 설정, 데이터 추가
        return redirect('/boards'); // list 페이지로 이동
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 엘로퀀트(orm)
        $boards = Boards::find($id);

        return view('edit')->with('data', $boards);
        // return view('edit')->with('data', Boards::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // v002 add start

        // 유효성 검사 방법 1
        // id를 리퀘스트 객체로 병합
        $arr = ['id' => $id];
        // $request->merge($arr);
        $request->request->add($arr);

        // $request->validate([
        //     'title' => 'required|between:3,30'
        //     ,'content' => 'required|max:1000'
        //     ,'id' => 'required|integer'
        // ]);
        // 숫자 체크 : numeric(정수), integer(데이터형에 상관없이)
        // * 유효성 체크 항목도 요구명세서에 작성

        // 유효성 검사 방법 2
        // ! 띄어쓰기 조심하기
        $validator = Validator::make($request->only('id', 'title', 'content'), [
                'id' => 'required|integer'
                ,'title' => 'required|between:3,30'
                ,'content' => 'required|max:1000'
            ]
        );
        if($validator->fails()){
            return redirect()->back()
            ->withErrors($validator)
            ->withInput($request->only('title', 'content'));
        }


        // $validator = Validator::make($request->only('id', 'title', 'content'), [
        //     'id'         => 'required|integer'
        //     ,'title'     => 'required|between:3,30'
        //     ,'content'   => 'required|max:1000'
        // ]);
        // if ($validator->fails()) {
        //         return redirect()->back()
        //             ->withErrors($validator)
        //             ->withInput($request->only('title', 'content')); // 필요한 값만 세션에 전달 가능
        //     }

        
        // v002 add end

        // 엘로퀀트
        $boards = Boards::find($id);
        // $boards->title = $request->title;
        // $boards->content = $request->content;
        // $boards->save();

        // 쿼리빌더 방식
        $board = DB::table('boards')
        ->where('id', $id)
        ->update([
            'title' => $request->input('title')
            ,'content' => $request->input('content')
        ]);
        // ->get();

        // ? view() 사용 -> url이 변하지 않음 / redirect() -> url 변함
        // ? url이 모두 같을 때(boards/{board})는 메소드(get, post, put, delete ... )로 분류
        // view
        // return view('detail')->with('data', Boards::findOrFail($id));

        // redirect
        // * 페이지 결과(update...)가 다를 때는 redirect를 통해 페이지 이동
        // return redirect('/boards/'.$id);
        return redirect()->route('boards.show', ['board' => $id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $boards = Boards::find($id);
        // $boards->delete();

        Boards::destroy($id);
        // ! DB::update()->delete() === 모든 코드 삭제
        return redirect('/boards');
    }

    // public function logincheck(){
    //     if(auth()->guest())
    //     return redirect()->route('users.login');
    // }
}
