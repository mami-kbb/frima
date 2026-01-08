<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Condition;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_all_items_on_index_page()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');
        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
            $response->assertSee('dummy.jpg');
        }
    }

    public function test_sold_items_have_sold_label_on_index_page()
    {
        $availableItem = Item::factory()->create([
            'name' => 'Available Item',
            'status' => 0,
        ]);

        $soldItem = Item::factory()->sold()->create([
            'name' => 'Sold Item',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee($availableItem->name);
        $response->assertSee($soldItem->name);
        $response->assertSee('<span class="sold-label">sold</span>',false);
        }

        public function test_authenticated_user_cannot_see_own_items_on_index_page()
        {
            $user = User::factory()->create();
            $myItem = Item::factory()->create([
                'user_id' => $user->id,
                'name' => 'My Item',
            ]);
            $otherItem = Item::factory()->create([
                'name' => 'Other User Item',
            ]);

            $response = $this->actingAs($user)->get('/');
            $response->assertStatus(200);
            $response->assertSee($otherItem->name);
            $response->assertDontSee($myItem->name);
        }

        public function test_mylist_tab_shows_only_liked_items()
        {
            $user = User::factory()->create();
            $likedItem = Item::factory()->create([
                'name' => 'Liked Item',
            ]);
            $notLikedItem = Item::factory()->create([
                'name' => 'Not Liked Item',
            ]);

            $user->likedItems()->attach($likedItem->id);

            $response = $this->actingAs($user)->get('/?tab=mylist');
            $response->assertStatus(200);
            $response->assertSee($likedItem->name);
            $response->assertDontSee($notLikedItem->name);

        }

        public function test_sold_items_have_sold_label_on_mylist(){
            $user = User::factory()->create();
            $availableItem = Item::factory()->create([
                'name' => 'Available Item',
                'status' => 0,
            ]);

            $soldItem = Item::factory()->sold()->create([
                'name' => 'Sold Item',
            ]);

            $user->likedItems()->attach([
                $availableItem->id,
                $soldItem->id,
            ]);

            $response = $this->actingAs($user)->get('/?tab=mylist');
            $response->assertStatus(200);

            $response->assertSee($availableItem->name);
            $response->assertSee($soldItem->name);
            $response->assertSee('<span class="sold-label">sold</span>',false);
        }

        public function test_search_filters_items_by_partial_name_match()
        {
            $matchItem = Item::factory()->create([
                'name' => 'iPhone 14 Pro',
            ]);
            $notMatchItem = Item::factory()->create([
                'name' => 'MacBook Air',
            ]);

            $response = $this->get('/?keyword=iPhone');
            $response->assertStatus(200);

            $response->assertSee($matchItem->name);
            $response->assertDontSee($notMatchItem->name);
        }

        public function test_search_keyword_is_kept_when_moving_to_mylist_tab()
        {
            $user = User::factory()->create();
            $matchItem = Item::factory()->create([
                'name' => 'iPhone 14 Pro',
            ]);
            $notMatchItem = Item::factory()->create([
                'name' => 'MacBook Air',
            ]);

            $user->likedItems()->attach([
                $matchItem->id,
                $notMatchItem->id,
            ]);

            $response = $this->actingAs($user)->get('/?tab=mylist&keyword=iPhone');
            $response->assertStatus(200);

            $response->assertSee($matchItem->name);
            $response->assertDontSee($notMatchItem->name);
            $response->assertSee('value="iPhone"',false);
        }

        public function test_guest_user_sees_empty_mylist()
        {
            $user = User::factory()->create();
            $item = Item::factory()->create([
                'name' => 'Liked Item',
            ]);

            $user->likedItems()->attach($item->id);

            $response = $this->get('/?tab=mylist');
            $response->assertStatus(200);
            $response->assertDontSee('Liked Item');
        }
}
