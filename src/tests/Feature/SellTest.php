<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_item_with_required_fields()
    {
        Storage::fake('public');

        $user = User::factory()->hasProfile()->create();
        $category = Category::factory()->create();
        $condition = Condition::factory()->create();

        $this->actingAs($user);

        $this->get('/sell')->assertStatus(200);

        $response = $this->post('/sell', [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'item_image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_item' => [$category->id],
            'condition_id' => $condition->id,
            'description' => 'これはテスト用の商品説明です',
            'price' => 5000,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'condition_id' => $condition->id,
            'description' => 'これはテスト用の商品説明です',
            'price' => 5000,
        ]);

        $item = Item::first();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->assertExists($item->image);
    }
}
