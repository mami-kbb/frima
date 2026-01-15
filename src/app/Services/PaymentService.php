<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Item;

class PaymentService
{
    public function createCheckoutSession(Item $item, string $paymentMethod): string
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentTypes = $paymentMethod === 'card'
            ? ['card']
            : ['konbini'];

        $session = CheckoutSession::create([
            'payment_method_types' => $paymentTypes,
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('purchase.success', ['item' => $item->id]),
            'cancel_url' => route('purchase.cancel'),
        ]);

        return $session->url;
    }
}
