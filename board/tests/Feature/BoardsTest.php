<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Boards;

class BoardsTest extends TestCase
// php artisan make:test BoardsTest
// 파일 이름의 끝이 Test로 끝날 것 -> Test가 아니면 실행이 안 됨
{
    use RefreshDatabase; // 테스트 완료 후 DB 초기화를 위한 트레이트
    use DatabaseMigrations; // DB Migration

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_게스트_리다이렉트(){
        // 메서드 이름은 test로 시작해야 함
        $response = $this->get('/boards');

        $response->assertRedirect('/users/login');
        // test 실행 : php artisan test
    }

    public function test_index_유저인증(){
        // 테스트용 유저 생성
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => 'test'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $response = $this->actingAs($user)->get('/boards');
        $this->assertAuthenticatedAs($user);
    }

    public function test_index_유저인증_뷰반환() {
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => 'test'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $response = $this->actingAs($user)->get('/boards');

        $response->assertViewIs('List');
    }

    public function test_index_유저인증_뷰반환_데이터확인() {
        $user = new user([
            'email' => 'aa@aa.aa'
            ,'name' => 'test'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $board1 = new Boards([
            'title' => 'test1'
            ,'content' => 'test1'
        ]);
        $board1->save();

        $board2 = new Boards([
            'title' => 'test2'
            ,'content' => 'test2'
        ]);
        $board2->save();

        $response = $this->actingAs($user)->get('/boards');

        $response->assertViewHas('data');
        $response->assertSee('test1');
        $response->assertSee('test2');
    }
}
