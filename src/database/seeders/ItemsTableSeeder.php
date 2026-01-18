<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disk = Storage::disk('public');

    $disk->makeDirectory('images');

    $images = [
        'Clock.jpg',
        'HDD.jpg',
        'onion.jpg',
        'Shoes.jpg',
        'Notepc.jpg',
        'Mic.jpg',
        'Bag.jpg',
        'Tumbler.jpg',
        'Coffee_Grinder.jpg',
        'Makeset.jpg',
    ];

    foreach ($images as $image) {
        $source = resource_path('sample-images/' . $image);
        $dest   = 'images/' . $image;

        if (File::exists($source) && !$disk->exists($dest)) {
            $disk->put($dest, File::get($source));
        }
    }

        DB::table('items')->insert([
            [
                'user_id' => 1,
                'name' => '腕時計',
                'image' => 'images/Clock.jpg',
                'condition_id' => 1,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
            ],
            [
                'user_id' => 2,
                'name' => 'HDD',
                'image' => 'images/HDD.jpg',
                'condition_id' => 2,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'image' => 'images/onion.jpg',
                'condition_id' => 3,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'image' => 'images/Shoes.jpg',
                'condition_id' => 4,
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'image' => 'images/Notepc.jpg',
                'condition_id' => 1,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
            ],
            [
                'user_id' => 2,
                'name' => 'マイク',
                'image' => 'images/Mic.jpg',
                'condition_id' => 2,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'image' => 'images/Bag.jpg',
                'condition_id' => 3,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
            ],
            [
                'user_id' => 1,
                'name' => 'タンブラー',
                'image' => 'images/Tumbler.jpg',
                'condition_id' => 4,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'price' => 500,
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'image' => 'images/Coffee_Grinder.jpg',
                'condition_id' => 1,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'image' => 'images/Makeset.jpg',
                'condition_id' => 2,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
            ],
            ]);
    }
}
