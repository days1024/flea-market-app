<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; 

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $categories = [
            ['slug' => 'fashion', 'category' => 'ファッション'],
            ['slug' => 'home', 'category' => '家電'],
            ['slug' => 'interior', 'category' => 'インテリア'],
            ['slug' => 'ladies', 'category' => 'レディース'],
            ['slug' => 'mens', 'category' => 'メンズ'],
            ['slug' => 'cosmetics', 'category' => 'コスメ'],
            ['slug' => 'book', 'category' => '本'],
            ['slug' => 'game', 'category' => 'ゲーム'],
            ['slug' => 'sports', 'category' => 'スポーツ'],
            ['slug' => 'kitchen', 'category' => 'キッチン'],
            ['slug' => 'handmade', 'category' => 'ハンドメイド'],
            ['slug' => 'accessories', 'category' => 'アクセサリー'],
            ['slug' => 'toys', 'category' => 'おもちゃ'],
            ['slug' => 'kids', 'category' => 'ベビー・キッズ'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
