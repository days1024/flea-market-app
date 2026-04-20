<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
class ItemCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
   public function test_item_listing_can_be_saved_with_required_information()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create([
        'category' => 'ファッション',
        'slug' => 'fashion',
    ]);

   
    $this->get('/sell')->assertStatus(200);

    $response = $this->post('/sell', [
        'category' => [$category->slug],
        'condition' => 'good',
        'name' => 'テスト商品',
        'brand' => 'Nike',
        'description' => 'これはテスト用の商品説明です',
        'price' => 5000,
        'image' => UploadedFile::fake()->create(
            'test.jpg',
            100,
            'image/jpeg'
        ),
    ]);


    $response->assertStatus(302);

   $item = Item::where('user_id', $user->id)->latest()->first();

    $this->assertNotNull($item);

    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'user_id' => $user->id,
        'condition' => 'good',
        'name' => 'テスト商品',
        'brand' => 'Nike',
        'description' => 'これはテスト用の商品説明です',
        'price' => 5000,
    ]);

    $this->assertStringContainsString('images/', $item->image);

    $this->assertDatabaseHas('item_categories', [
        'item_id' => $item->id,
        'category_id' => $category->id,
    ]);
}
}
