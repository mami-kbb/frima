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
        <h2>住所の変更</h2>
    </div>
    <div class="address-form">
        <form action="/purchase/address/{{ $item->id }}" class="form" method="post">
            @csrf
            <div class="form-group">
                <label class="item-label" for="postal-code">郵便番号</label>
                <input id="postal-code" type="text" class="form-input" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
                <div class="form__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="item-label" for="address">住所</label>
                <input id="address" type="text" class="form-input" name="address" value="{{ old('address', $profile->address) }}">
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="item-label" for="building">建物名</label>
                <input id="building" type="text" class="form-input" name="building" value="{{ old('building', $profile->building) }}">
            </div>
            <div class="form-btm">
                <button class="form-btn-submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection