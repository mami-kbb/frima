<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Order;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_info_on_profile_page()
    {
        $user = User::factory()->hasProfile([
            'profile_image' => 'dummy.jpg',
        ])->create();

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sell Item',
        ]);

        $buyItem = Item::factory()->sold()->create([
            'name' => 'Bought Item',
        ]);

        Order::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('dummy.jpg');
        $response->assertSee($sellItem->name);

        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee($buyItem->name);
    }

    public function test_profile_edit_page_displays_existing_values_as_initial_values()
    {
        $user = User::factory()->hasProfile([
            'profile_image' => 'dummy.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
        ])->create([
            'name' => 'テスト太郎',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertSee('value="テスト太郎"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="東京都渋谷区1-2-3"', false);
        $response->assertSee('dummy.jpg');
    }
}
