<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class PaymentMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
   public function test_payment_method_is_saved_and_reflected()
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
    ]);

    $this->get("/purchase/{$item->id}")
        ->assertStatus(200);

    $response = $this->post("/purchase/{$item->id}", [
        'payment' => 'card',
    ]);

    $response = $this->get("/purchase/{$item->id}");

     $response->assertSee('カード払い');
}
}
