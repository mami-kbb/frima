<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_item_and_like_count_increases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('data-testid="like-count"', false);
        $response->assertSee('>1<', false);
    }

    public function test_liked_item_shows_liked_icon()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('heart_logo_pink.png');
    }

    public function test_user_can_unlike_item_and_like_count_decreases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $this->post("/item/{$item->id}/like");
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('data-testid="like-count"', false);
        $response->assertSee('>0<', false);
    }
}
