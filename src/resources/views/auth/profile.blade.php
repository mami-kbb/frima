@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="profile-content">
    <div class="profile">
        <div class="user-info">
            @if ($profile && $profile->profile_image)
            <img src="{{ asset('storage/'.$profile->profile_image) }}" alt="プロフィール画像" class="user-img">
            @else
            <div class="user-img dummy"></div>
            @endif
            <p class="user-name">{{ $user->name }}</p>
        </div>
        <a href="/mypage/profile" class="profile-edit">プロフィールを編集</a>
    </div>
    <div class="tabs">
        <a href="/mypage?page=sell" class="{{ $tab == 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?page=buy" class="{{ $tab == 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="item-list">
        @foreach ($items as $item)
        <a href="/item/{{ $item->id }}" class="item-card">
            <div class="image-wrapper">
                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="item-image">
                @if ($item->is_sold)
                <span class="sold-label">sold</span>
                @endif
            </div>
            <p class="item-name">{{ $item->name }}</p>
        </a>
        @endforeach
    </div>
</div>
@endsection