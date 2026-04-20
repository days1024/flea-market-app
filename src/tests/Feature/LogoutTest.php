<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_user_can_logout()
{
    // ユーザー作成
    $user = User::factory()->create();

    // ログイン状態にする
    $this->actingAs($user);

    // ログアウト実行
    $response = $this->post('/logout');

    // ログアウトできてるか確認
    $this->assertGuest();

    // リダイレクト確認（ログイン画面など）
    $response->assertRedirect('/login');
}
}
