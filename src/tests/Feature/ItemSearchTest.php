<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ItemSreachTest extends TestCase
{
    use RefreshDatabase;

    

public function test_user_can_search_items_by_partial_match()
{
    $user = User::factory()->create();

    $item1 = Item::factory()->create([
        'user_id' => $user->id,
        'name' => 'りんごジュース',
    ]);
    $item2 = Item::factory()->create([
        'user_id' => $user->id,
        'name' => 'みかんジュース',
    ]);

    $response = $this->get('/?keyword=りんご');

    $response->assertSee('りんごジュース');
    $response->assertDontSee('みかんジュース');
}

public function test_search_keyword_is_kept_when_navigating_to_mylist()
{
    $user = User::factory()->create();

    $item = Item::factory()->create([
        'user_id' => $user->id,
        'name' => 'りんごジュース',
    ]);

    $this->actingAs($user);
    $response = $this->get('/?keyword=りんご');

    $response = $this->get('/?tab=mylist&keyword=りんご');

    $response->assertSee('value="りんご"', false);
}
}