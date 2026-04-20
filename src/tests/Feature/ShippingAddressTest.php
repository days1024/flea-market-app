<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Address;

class ShippingAddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
   public function test_shipping_address_is_reflected_in_purchase_page()
{
    // 1. ユーザーにログインする
    $user = User::factory()->create();
    $this->actingAs($user);

    // 送付先情報（プロフィール）作成
    Profile::create([
        'user_id' => $user->id,
        'user_name' => 'テストユーザー',
        'post_code' => '123-4567',
        'address' => '東京都渋谷区',
    ]);

    Address::create([
        'user_id' => $user->id,
        'post_code' => '123-4567',
        'address' => '東京都渋谷区',
    ]);

    $owner = User::factory()->create();

    // 2. 商品作成
    $item = Item::factory()->create([
        'user_id' => $owner->id,
        'buyer_id' => null,
    ]);

    // 3. 送付先住所変更画面（更新）
    $this->post("/purchase/address/{$item->id}", [
        'post_code' => '987-6543',
        'address' => '大阪府大阪市',
    ])->assertStatus(302);

    // DB更新確認
    $this->assertDatabaseHas('addresses', [
        'user_id' => $user->id,
        'post_code' => '987-6543',
        'address' => '大阪府大阪市',
    ]);

    // 4. 商品購入画面を再度開く
    $response = $this->get("/purchase/{$item->id}");

    $response->assertStatus(200);

    // 5. 画面に反映されているか確認
    $response->assertSee('987-6543');
    $response->assertSee('大阪府大阪市');
}



   public function test_purchase_with_shipping_address_is_saved()
{
     $owner = User::factory()->create();
    $buyer = User::factory()->create();

    Profile::create([
    'user_id' => $buyer->id,
    'user_name' => 'テストユーザー',
    'post_code' => '123-4567',
    'address' => '東京都',
    ]);

    Address::create([
        'user_id' => $buyer->id,
        'post_code' => '123-4567',
        'address' => '東京都渋谷区',
    ]);

    $this->actingAs($buyer);

    $item = Item::factory()->create([
        'user_id' => $owner->id,
        'buyer_id' => null,
        'name' => 'テスト商品',
    ]);

    $this->get("/purchase/{$item->id}")
        ->assertStatus(200);

    

    $this->post("/purchase/address/{$item->id}", [
        'post_code' => '987-6543',
        'address' => '大阪府大阪市',
    ])->assertStatus(302);

    $address = Address::where('user_id', $buyer->id)->latest()->first();

    $this->post("/purchase/{$item->id}", [
        'payment' => 'card',
        'address_id' => $address->id,
    ])->assertStatus(302);


    $item->refresh();

    $this->assertEquals($buyer->id, $item->buyer_id);


    $this->assertDatabaseHas('items', [
    'id' => $item->id,
    'buyer_id' => $buyer->id,
    'address_id' => $address->id,
    ]);
}
}
