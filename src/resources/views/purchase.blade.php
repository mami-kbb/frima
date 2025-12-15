@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="purchase-content">
    <div class="content-left">
        <div class="item-group">
            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="item-img">
            <div class="item-description">
                <p class="item-name">{{ $item->name }}</p>
                <p class="item-price">{{ $item->price }}</p>
            </div>
        </div>
        <div class="pay-group">
            <form action="/purchase/{item_id}" method="post">
                @csrf
                <select name="payment_method" id="payment_method">
                    <option value="convenience" {{ $payment_method === 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                    <option value="card" {{ $payment_method === 'card' ? 'selected' : '' }}>カード支払い</option>
                </select>
                <div class="address-group">
                    <div class="address-title">
                        <p class="address-label">配送先</p>
                        <a href="/purchase/address/{item_id}" class="address-change">変更する</a>
                    </div>
                    <input type="text" class="postal-code" name="postal_code" value="{{ $address->postal_code }}" readonly>
                    <input type="text" class="address" name="address" value="{{ $address->address }} readonly">
                    <input type="text" class="building" name="building" value="{{ $address->building }}" readonly>
                </div>
            </div>
            <div class="content-right">
                <div class="content">
                    <div class="price">
                        <p>商品代金</p>
                        <p>&yen;<span>{{ $item->price }}</span></p>
                    </div>
                    <div class="payment">
                        <p>支払い方法</p>
                        <p id="payment_display">{{ $payment_method === 'convenience' ? 'コンビニ支払い' : 'カード支払い' }}</p>
                    </div>
                </div>
                <buttons type="submit">購入する</buttons>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function () {
    const text = this.value === 'convenience' ? 'コンビニ支払い' : 'カード支払い';
    document.getElementById('payment_display').innerText = text;
});
</script>

@endsection