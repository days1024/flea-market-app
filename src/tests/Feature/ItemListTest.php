<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

   public function test_user_can_view_product_list_with_image_and_name()
{
    $user = User::factory()->create();

    $item = Item::factory()->create([
        'name' => 'テスト商品',
        'image' => 'test.jpg',
        'user_id' => User::factory()->create()->id, 
    ]);

    $this->actingAs($user);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('テスト商品');
    $response->assertSee('test.jpg');
}

    public function test_sold_label_is_displayed_for_purchased_items()
{
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    Item::factory()->create([
        'user_id' => $seller->id,
        'buyer_id' => $buyer->id,
        'name' => 'テスト商品',
    ]);

    $this->actingAs($buyer);

    $response = $this->get('/');

    $response->assertSee('SOLD');
}

    public function test_user_cannot_see_own_items_in_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
            'image' => 'images/default.jpg',
        ]);

        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'image' => 'images/default.jpg',
        ]);

        $response = $this->get('/');

        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

   
}