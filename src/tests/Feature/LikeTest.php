<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class LikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_user_can_like_item_and_like_count_increases()
{
    $user = User::factory()->create();

    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    $response = $this->post("/items/{$item->id}/like");

    $item->refresh();

    $this->assertEquals(1, $item->likes()->count());
}

    public function test_like_icon_changes_when_item_is_liked()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // いいね押下
    $this->post("/items/{$item->id}/like");

    // 再取得
    $response = $this->get("/items/{$item->id}");

    // ピンク（いいね済み）が表示されているか
    $response->assertSee('ハートロゴ_ピンク.png');

    // デフォルトが表示されていないことも確認（任意）
    $response->assertDontSee('ハートロゴ_デフォルト.png');
}

    public function test_user_can_unlike_item_and_like_count_decreases()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // ① まずいいねする
    $this->post("/items/{$item->id}/like");

    $item->refresh();
    $this->assertEquals(1, $item->likes()->count());

    // ② もう一度押して解除
    $this->post("/items/{$item->id}/like");

    $item->refresh();
    $this->assertEquals(0, $item->likes()->count());
}
}
