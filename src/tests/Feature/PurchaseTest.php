<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
     use RefreshDatabase;

    public function test_user_can_purchase_item()
{
    // 1. ユーザーにログインする
    $owner = User::factory()->create();
    $buyer = User::factory()->create();

    Profile::create([
    'user_id' => $buyer->id,
    'user_name' => 'テストユーザー',
    'post_code' => '123-4567',
    'address' => '東京都',
]);

    $this->actingAs($buyer);

    // 2. 商品購入画面を開く
    $item = Item::factory()->create([
        'user_id' => $owner->id,
        'buyer_id' => null,
    ]);

    $this->get("/purchase/{$item->id}")
        ->assertStatus(200);

    // 3. 支払方法を選択して「購入する」ボタン押下
    $response = $this->post("/purchase/{$item->id}", [
        'payment' => 'card',
    ]);

    $item->refresh();


    // 4. DB確認（即購入完了）
    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'buyer_id' => $buyer->id, 
    ]);

    $response->assertStatus(302);
}

    public function test_purchased_item_shows_sold_on_index()
{
    $owner = User::factory()->create();
    $buyer = User::factory()->create();

    Profile::create([
    'user_id' => $buyer->id,
    'user_name' => 'テストユーザー',
    'post_code' => '123-4567',
    'address' => '東京都',
]);

    $this->actingAs($buyer);

    // 2. 商品購入画面を開く
    $item = Item::factory()->create([
        'user_id' => $owner->id,
        'buyer_id' => null,
    ]);

    $this->get("/purchase/{$item->id}")
        ->assertStatus(200);

    // 3. 支払方法を選択して「購入する」ボタン押下
    $response = $this->post("/purchase/{$item->id}", [
        'payment' => 'card',
    ]);

    $item->refresh();


    // 4. DB確認（即購入完了）
    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'buyer_id' => $buyer->id, 
    ]);

    // 4. 一覧画面を表示する
    $response = $this->get('/');

    $response->assertStatus(200);

    // 5. SOLD表示確認（重要）
    $response->assertSee('SOLD');
}

    public function test_purchased_item_shows_in_profile()
{
    $owner = User::factory()->create();
    $buyer = User::factory()->create();

    Profile::create([
    'user_id' => $buyer->id,
    'user_name' => 'テストユーザー',
    'post_code' => '123-4567',
    'address' => '東京都',
    ]);

    $this->actingAs($buyer);

    $item = Item::factory()->create([
        'user_id' => $owner->id,
        'buyer_id' => null,
        'name' => 'テスト商品',
    ]);

    $this->get("/purchase/{$item->id}")
        ->assertStatus(200);


    $response = $this->post("/purchase/{$item->id}", [
        'payment' => 'card',
    ]);

    $item->refresh();


    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'buyer_id' => $buyer->id, 
    ]);


    $response = $this->get('/mypage?page=buy');

    $response->assertStatus(200);

    $response->assertSee('テスト商品');
}
    
}