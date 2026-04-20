<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_name_is_required_for_register()
{
    $response = $this->post('/register', [
        'name' => '', 
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['name'=> 'お名前を入力してください']);
}
    
    public function test_email_is_required_for_register()
{
    $response = $this->post('/register', [
        'name' => 'test',
        'email' => '',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
}

    public function test_password_is_required_for_register()
{
    $response = $this->post('/register', [
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
}

    public function test_password_must_be_at_least_8_characters()
{
    $response = $this->post('/register', [
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => 'pass',
        'password_confirmation' => 'pass',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
}
    public function test_password_confirmation_must_match()
{
    $response = $this->post('/register', [
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'passwword',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
}
}
