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
    <form action="/purchase/{{ $item->id }}" method="post">
        @csrf
        <div class="content-left">
            <div class="item-group">
                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="item-img">
                <div class="item-description">
                    <p class="item-name">{{ $item->name }}</p>
                    <p class="item-price"><span>&yen;</span>{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="payment-group">
                <p class="payment-title">支払い方法</p>
                <div class="custom-select" id="paymentSelect">
                    <div class="selected" id="selectedPayment">選択してください</div>
                    <ul class="options">
                        <li data-value="convenience">コンビニ支払い</li>
                        <li data-value="card">カード支払い</li>
                    </ul>
                </div>
                <input type="hidden" name="payment_method" id="payment_method" value="{{ $payment_method ?? 'convenience' }}">
            </div>
            <div class="address-group">
                <div class="address-title">
                    <p class="address-label">配送先</p>
                    <a href="/purchase/address/{{ $item->id }}" class="address-change">変更する</a>
                </div>
                <input type="text" class="postal-code" name="postal_code" value="{{ $address->postal_code }}" readonly>
                <input type="text" class="address" name="address" value="{{ $address->address }}" readonly>
                <input type="text" class="building" name="building" value="{{ $address->building }}" readonly>
            </div>
        </div>
        <div class="content-right">
            <div class="purchase-info">
                <div class="price">
                    <p>商品代金</p>
                    <p>&yen;<span>{{ number_format($item->price) }}</span></p>
                </div>
                <div class="payment">
                    <p>支払い方法</p>
                    <p id="payment_display">{{ $payment_method === 'convenience' ? 'コンビニ支払い' : 'カード支払い' }}</p>
                </div>
            </div>
            <button class="form-btn" type="submit">購入する</button>
        </div>
    </form>
</div>

<script>
const select = document.getElementById('paymentSelect');
const selected = document.getElementById('selectedPayment');
const options = document.querySelectorAll('.options li');
const hiddenInput = document.getElementById('payment_method');
const initialPayment = "{{ $payment_method }}";

if (initialPayment) {
    const option = document.querySelector(
        `.options li[data-value="${initialPayment}"]`
    );
    if (option) {
        document.getElementById('selectedPayment').innerText = option.textContent;
        document.getElementById('payment_method').value = initialPayment;
        document.getElementById('payment_display').innerText = option.textContent;
    }
}

select.addEventListener('click', () => {
    select.classList.toggle('open');
    select.querySelector('.options').style.display = select.classList.contains('open') ? 'block' : 'none';
});

options.forEach(option => {
    option.addEventListener('click', (e) => {
        e.stopPropagation();

        selected.textContent = option.textContent;
        hiddenInput.value = option.dataset.value;

        select.classList.remove('open');
        select.querySelector('.options').style.display = 'none';

        document.getElementById('payment_display').innerText = option.textContent;
    });
});
</script>

@endsection