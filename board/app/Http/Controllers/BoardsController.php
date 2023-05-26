<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Eloquent\SoftDeletes;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        return redirect()->route('board.show', ['board' => $id]);

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
}
