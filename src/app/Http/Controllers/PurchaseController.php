<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function show(Request $request, Item $item)
    {
        $profile = auth()->user()->profile;

        $address = session('purchase_address', [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        $payment_method = $request->payment_method ?? 'convenience';

        return view('purchase', compact('item', 'address', 'payment_method'));
    }

    public function editAddress(Item $item)
    {
        $profile = auth()->user()->profile;

        return view('auth.address', compact('profile'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        $validated = $request->validated();

        session([
            'purchase_address' => $validated
        ]);

        return redirect("/purchase/{$item->id}");
    }

    public function store(Request $request, Item $item)
    {
        $address = session('purchase_address') ?? auth()->user()->profile;

        $order = Order::create([
            'item_id' => $item->id,
            'buyer_id' => auth()->id(),
            'postal_code' => $address['postal_code'],
            'address' => $address['address'],
            'building' => $address['building'],
            'payment_method' => $request->payment_method,
            'total_price' => $item->price,
        ]);

        $item->update(['sold' => 1]);

        return redirect('/');
    }
}
