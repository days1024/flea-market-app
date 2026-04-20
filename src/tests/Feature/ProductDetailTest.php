<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Category;


class ProductDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_item_detail_page_displays_all_required_information()
    {
        $user = User::factory()->create([
            'name' => '山田次郎'
        ]);
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/test.jpg',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 10000,
            'description' => '商品説明テスト',
            'condition' => 'good',
        ]);

        $category = \App\Models\Category::create([
            'category' => 'ファッション',
            'slug' => 'fashion',
            
        ]);

        $item->categories()->attach($category->id);

        $commentUser = User::factory()->create([
            'name' => '山田太郎'
        ]);

        $profile = Profile::create([
            'user_id' => $commentUser->id,
            'user_name' => 'テストユーザー',
            'post_code' => '123-4567',
            'address' => '東京都',
        ]);
        
        
        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'コメントテスト',
        ]);
        
        $response = $this->get("/items/{$item->id}");
        
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('10000');
        $response->assertSee('商品説明テスト');
        $response->assertSee('目立った傷や汚れなし');
        $response->assertSee('ファッション');
        $response->assertSee('コメントテスト');
        $response->assertSee('テストユーザー');
    }

    public function test_item_detail_page_displays_multiple_categories()
{
    $user = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $user->id,
    ]);

    // カテゴリを2つ作成
    $category1 = Category::create([
        'category' => 'ファッション',
        'slug' => 'fashion',
    ]);

    $category2 = Category::create([
        'category' => '家電',
        'slug' => 'electronics',
    ]);

    // 商品に複数カテゴリを紐づけ
    $item->categories()->attach([
        $category1->id,
        $category2->id,
    ]);

    $response = $this->get("/items/{$item->id}");

    // 表示確認
    $response->assertSee('ファッション');
    $response->assertSee('家電');
}

}
