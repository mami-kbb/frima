<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Services\PaymentService;

class PurchaseController extends Controller
{
    public function show(Request $request, Item $item)
    {
        $profile = auth()->user()->profile;

        $address = (object) session('purchase_address', [
                'postal_code' => $profile->postal_code,
                'address' => $profile->address,
                'building' => $profile->building,
            ]);

        $payment_method = session('payment_method', 'convenience');

        return view('purchase', compact('item', 'address', 'payment_method'));
    }

    public function editAddress(Item $item)
    {
        if (session()->has('purchase_address')) {
            $profile = (object) session('purchase_address');
        } else {
            $profile = auth()->user()->profile;
        }

        return view('auth.address', compact('item', 'profile'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        session([
            'purchase_address' => $request->validated(),
        ]);

        return redirect("/purchase/{$item->id}");
    }

    public function store(Request $request, Item $item, PaymentService $paymentService)
    {
        $paymentMethod = $request->payment_method ?? 'convenience';

        if(!in_array($paymentMethod, ['card', 'convenience'])) {
            abort(400);
        }

        if (session()->has('purchase_address')) {
            $address = session('purchase_address');
        } else {
            $address = auth()->user()->profile->only([
                'postal_code',
                'address',
                'building'
            ]);
        }

        if ($paymentMethod === 'convenience') {
            Order::create([
                'item_id' => $item->id,
                'buyer_id' => auth()->id(),
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building' => $address['building'],
                'payment_method' => 'convenience',
                'total_price' => $item->price,
            ]);

            $item->update(['status' => 1]);

            session()->forget('purchase_address');
        }

        $checkoutUrl = $paymentService->createCheckoutSession($item,$paymentMethod);

        return redirect($checkoutUrl);
    }

    public function success(Request $request)
    {
        $item = Item::findOrFail($request->query('item'));

        if ($item->status === 1) {
        return redirect('/');
    }

        if (session()->has('purchase_address')) {
            $address = session('purchase_address');
        } else {
            $address = auth()->user()->profile->only([
                'postal_code',
                'address',
                'building'
            ]);
        }

        Order::create([
            'item_id' => $item->id,
            'buyer_id' => auth()->id(),
            'postal_code' => $address['postal_code'],
            'address' => $address['address'],
            'building' => $address['building'],
            'payment_method' => 'card',
            'total_price' => $item->price,
        ]);

        $item->update(['status' => 1]);

        session()->forget('purchase_address');

        return redirect('/');
    }
}
