<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Services\PaymentService;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        $this->mock(\App\Services\PaymentService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->andReturn('/dummy-stripe-url');
    });

        $buyer = User::factory()->hasProfile()->create();
        $item = Item::factory()->create();

        $this->actingAs($buyer);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $this->post("/purchase/{$item->id}")->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_sold_item_has_sold_label()
    {
        $this->mock(\App\Services\PaymentService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->andReturn('/dummy-stripe-url');
    });

        $buyer = User::factory()->hasProfile()->create();
        $item = Item::factory()->create([
            'status' => 0,
        ]);

        $this->actingAs($buyer);

        $this->get("/purchase/{$item->id}")->assertStatus(200);

        $this->post("/purchase/{$item->id}")->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('sold');
    }

    public function test_purchased_item_is_displayed_on_profile_page()
    {
        $this->mock(\App\Services\PaymentService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->andReturn('/dummy-stripe-url');
    });

        $buyer = User::factory()->hasProfile([
            'profile_image' => 'dummy.jpg',
        ])->create();
        $item = Item::factory()->create();

        $this->actingAs($buyer);

        $this->get("/purchase/{$item->id}")->assertStatus(200);
        $this->post("/purchase/{$item->id}")->assertStatus(302);

        $response = $this->get("/mypage?page=buy");
        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    public function test_selected_payment_method_is_reflected_on_purchase_page()
    {
        $buyer = User::factory()->hasProfile()->create();
        $item = Item::factory()->create();

        $this->actingAs($buyer);

        $this->post('/purchase/payment-method', [
            'payment_method' => 'card'
        ])->assertStatus(200);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    public function test_update_shipping_address_is_reflected_on_purchase_page()
    {
        $this->mock(\App\Services\PaymentService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->andReturn('/dummy-stripe-url');
    });

        $buyer = User::factory()->hasProfile()->create();
        $item = Item::factory()->create();

        $this->actingAs($buyer);

        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル',
        ])->assertStatus(302);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストビル');
    }

    public function test_update_shipping_address_is_saved_with_order()
    {
        $this->mock(\App\Services\PaymentService::class, function ($mock) {
        $mock->shouldReceive('createCheckoutSession')
            ->andReturn('/dummy-stripe-url');
    });

        $buyer = User::factory()->hasProfile()->create();
        $item = Item::factory()->create();

        $this->actingAs($buyer);

        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル',
        ])->assertStatus(302);

        $response = $this->post("/purchase/{$item->id}");
        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル',
        ]);
    }
}
