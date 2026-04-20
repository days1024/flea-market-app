<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class UserUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_profile_edit_page_displays_saved_values_as_default()
{

    $user = User::factory()->create();

    $this->actingAs($user);


    Profile::create([
        'user_id' => $user->id,
        'user_name' => 'テストユーザー',
        'profile_image' => 'images/profile.png',
        'post_code' => '123-4567',
        'address' => '東京都渋谷区',
    ]);


    $response = $this->get('/mypage/profile');

    $response->assertStatus(200);

    $response->assertSee('テストユーザー');      
    $response->assertSee('images/profile.png');  
    $response->assertSee('123-4567');             
    $response->assertSee('東京都渋谷区');         
}
}
