<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $categories = Category::all();
        $items = [
        [
            'image' => 'images/Armani+Mens+Clock.jpg',
            'condition' => 'excellent',
            'name' => '腕時計',
            'brand' => 'Rolex',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
        ],

        [
            'image' => 'images/HDD+Hard+Disk.jpg',
            'condition' => 'good',
            'name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
        ],

        [
            'image' => 'images/iLoveIMG+d.jpg',
            'condition' => 'fair',
            'name' => '玉ねぎ3束',
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
        ],

        [
            'image' => 'images/Leather+Shoes+Product+Photo.jpg',
            'condition' => 'poor',
            'name' => '革靴',
            'brand' => '',
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
        ],

        [
            'image' => 'images/Living+Room+Laptop.jpg',
            'condition' => 'excellent',
            'name' => 'ノートPC',
            'brand' => '',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
        ],
        [
            'image' => 'images/Music+Mic+4632231.jpg',
            'condition' => 'good',
            'name' => 'マイク',
            'brand' => 'なし',
            'description' => '高音質のレコーディングマイク',
            'price' => '8000',
        ],

        [
            'image' => 'images/Purse+fashion+pocket.jpg',
            'condition' => 'fair',
            'name' => 'ショルダーバッグ',
            'brand' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
        ],

        [
            'image' => 'images/Tumbler+souvenir.jpg',
            'condition' => 'poor',
            'name' => 'タンブラー',
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => '500',
        ],

        [
            'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
            'condition' => 'excellent',
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
        ],

        [
            'image' => 'images/外出メイクアップセット.jpg',
            'condition' => 'good',
            'name' => 'メイクセット',
            'brand' => '',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
        ],
        ];
         foreach ($items as $index => $data) {

         $item = Item::create([
            ...$data,
            'user_id' => $users[$index % $users->count()]->id,
        ]);
        $randomCategories = $categories->random(2)->pluck('id');

        $item->categories()->attach($randomCategories);
    }
    }
}
