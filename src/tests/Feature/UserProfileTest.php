<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class UserProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_profile_page_displays_required_user_information()
{

    $user = User::factory()->create();

    $this->actingAs($user);


    Profile::create([
        'user_id' => $user->id,
        'user_name' => 'テストユーザー',
        'profile_image' => 'images/test-user.png',
        'post_code' => '123-4567',
        'address' => '東京都',
    ]);


    $sellItem = Item::factory()->create([
        'user_id' => $user->id,
        'name' => '出品商品A',
        'buyer_id' => null,
    ]);


    $buyItem = Item::factory()->create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入商品B',
        'buyer_id' => $user->id,
    ]);


    $response = $this->get('/mypage');

    $response->assertStatus(200);


    $response->assertSee('テストユーザー');          
    $response->assertSee('images/test-user.png');  

    
    $response = $this->get('/mypage?page=sell');

    $response->assertStatus(200);

    $response->assertSee('出品商品A');              
  

    $response = $this->get('/mypage?page=buy');

    $response->assertStatus(200);


       
    $response->assertSee('購入商品B');  
}
}
