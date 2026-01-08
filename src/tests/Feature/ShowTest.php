<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Profile;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_page_shows_required_information()
    {
        $user = User::factory()->create([
            'name' => 'Comment User'
        ]);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $condition = Condition::factory()->create([
            'condition' => '良好',
        ]);

        $category = Category::factory()->create([
            'category' => 'ファッション',
        ]);

        $item = Item::factory()->create([
            'name' => '腕時計',
            'brand_name' => 'Rolex',
            'price' => '15000',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'image' => 'dummy.jpg',
        ]);

        $item->categories()->attach($category->id);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'いい商品ですね',
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertSee('Rolex');
        $response->assertSee('15,000');
        $response->assertSee('スタイリッシュなデザインのメンズ腕時計');
        $response->assertSee('良好');
        $response->assertSee('ファッション');
        $response->assertSee('1');
        $response->assertSee('Comment User');
        $response->assertSee('いい商品ですね');
    }

    public function test_item_detail_page_shows_multiple_categories()
    {
        $item = Item::factory()->create([
            'name' => '腕時計',
        ]);

        $categories = Category::factory()->count(3)->create([
            'category' => 'dummy',
        ]);

        $categories[0]->update(['category' => 'ファッション']);
        $categories[1]->update(['category' => 'メンズ']);
        $categories[2]->update(['category' => 'アクセサリー']);

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee('アクセサリー');
    }
}
