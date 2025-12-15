@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="address-content">
    <div class="title">
        <h3>住所の変更</h3>
    </div>
    <div class="address-form">
        <form action="/purchase/address/{{ $item->id }}" class="form" method="post">
            @csrf
            <div class="form-group">
                <label for="postal-code">郵便番号</label>
                <input type="text" class="form-input" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
            </div>
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" class="form-input" name="address" value="{{ old('address', $profile->address) }}">
            </div>
            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" class="form-input" name="building" value="{{ old('building', $profile->building) }}">
            </div>
            <div class="form-btm">
                <button class="form-btn-submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection