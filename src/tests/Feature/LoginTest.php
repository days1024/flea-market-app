<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    use RefreshDatabase;
    public function test_email_is_required_for_login()
{
    $response = $this->post('/login', [
        'email' => '',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
}

    public function test_password_is_required_for_login()
{
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
}
    use RefreshDatabase;
    public function test_user_cannot_login_with_invalid_credentials()
{
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors([
        'email' => 'ログイン情報が登録されていません'
    ]);
}
    public function test_user_can_login()
{
    // ユーザー作成（DBに入れる）
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    // ログイン処理
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // ログインできてるか確認
    $this->assertAuthenticated();

    // リダイレクト確認
    $response->assertRedirect('/');
}
}
