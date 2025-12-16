@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="index-content">
    <div class="tabs">
        <a href="/?tab=recommend&keyword={{ request('keyword') }}" class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist&keyword={{ request('keyword') }}" class="{{ $tab == 'mylist' ? 'active' : '' }}">マイリスト</a>
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