<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_user_can_post_comment_and_comment_count_increases()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // 初期状態
    $this->assertEquals(0, $item->comments()->count());

    // コメント投稿
    $response = $this->post("/items/{$item->id}/comment", [
        'content' => 'テストコメント',
    ]);

    $response->assertStatus(302); // redirect想定

    $item->refresh();

    // コメントが保存されているか
    $this->assertEquals(1, $item->comments()->count());

    // コメント内容確認
    $this->assertDatabaseHas('comments', [
        'item_id' => $item->id,
        'user_id' => $user->id,
        'content' => 'テストコメント',
    ]);
}

    public function test_guest_cannot_post_comment()
{
    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // 未ログイン状態でコメント送信
    $response = $this->post("/items/{$item->id}/comment", [
        'content' => 'ログインなしコメント',
    ]);

    $response->assertRedirect('/login');

    // コメントが保存されていないことを確認
    $this->assertDatabaseMissing('comments', [
        'item_id' => $item->id,
        'content' => 'ログインなしコメント',
    ]);
}

    public function test_comment_validation_fails_when_content_is_empty()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // 空で送信
    $response = $this->post("/items/{$item->id}/comment", [
        'content' => '',
    ]);

    // バリデーションエラー確認
    $response->assertSessionHasErrors('content');

    // DBに保存されていないこと確認
    $this->assertDatabaseMissing('comments', [
        'item_id' => $item->id,
        'user_id' => $user->id,
    ]);
}

    public function test_comment_validation_fails_when_content_exceeds_255_chars()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $itemOwner = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $itemOwner->id,
    ]);

    // 255文字超え
    $longComment = str_repeat('あ', 256);

    $response = $this->post("/items/{$item->id}/comment", [
        'content' => $longComment,
    ]);

    // リダイレクト（バリデーションエラー）
    $response->assertSessionHasErrors('content');

    // DBに保存されていないこと確認
    $this->assertDatabaseMissing('comments', [
        'item_id' => $item->id,
        'content' => $longComment,
    ]);
}
}
