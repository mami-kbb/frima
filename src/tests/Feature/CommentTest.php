<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_comment_and_comment_count_increases()
    {
        $user = User::factory()->hasProfile([
            'profile_image' => 'dummy.jpg',
        ])->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('data-testid="comment-count"', false);
        $response->assertSee('1');
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'ゲストコメント',
        ]);
    }

    public function test_comment_validation_error_when_comment_is_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('comment');
    }

    public function test_comment_validation_error_when_comment_exceeds_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => $longComment,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('comment');
    }
}
