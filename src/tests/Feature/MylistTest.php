<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_only_liked_item_images()
{
    $user = User::factory()->create();
    $otherUser = User::factory()->create();


    $likedItem = Item::factory()->create([
        'user_id' => $user->id,
        'image' => 'images/liked.jpg',
    ]);

  
    $notLikedItem = Item::factory()->create([
        'user_id' => $otherUser->id,
        'image' => 'images/not-liked.jpg',
    ]);


    DB::table('likes')->insert([
        'user_id' => $user->id,
        'item_id' => $likedItem->id,
    ]);

    $this->actingAs($user);

    $response = $this->get('/?tab=mylist');

    $response->assertSee('images/liked.jpg');
    $response->assertDontSee('images/not-liked.jpg');
}

public function test_sold_label_is_displayed_in_mylist()
{
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $otherUser->id,
        'buyer_id' => $user->id,
        'image' => 'images/liked.jpg',
        'name' => 'いいね商品'
    ]);

    DB::table('likes')->insert([
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);

    $this->actingAs($user);


    $response = $this->get('/?tab=mylist');

    $response->assertSee('いいね商品');

    $response->assertSee('SOLD');
}

public function test_guest_cannot_see_mylist_items()
{
     $user = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $user->id,
        'name' => 'テスト商品',
    ]);

    // 未ログインのままマイリストへ
    $response = $this->get('/?tab=mylist');

    // 商品が表示されない
    $response->assertDontSee('テスト商品');
}


}