@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="show-content">
    <div class="image-area">
        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">
        @if ($item->status)
        <span class="sold-label">sold</span>
        @endif
    </div>
    <div class="description-area">
        <div class="item-title">
            <div class="item-title__name">
                <h3>{{ $item->name }}</h3>
                <p class="item-brand">{{ $item->brand_name }}</p>
            </div>

            <p class="item-price">&yen;<span>{{ number_format($item->price) }}</span>(税込)</p>

            <div class="item-action">
                <div class="item-liked">
                    <form action="/item/{{ $item->id }}/like" class="liked-form" method="post">
                        @csrf
                        <button class="like-btn">
                            @if($isLiked)
                            <img src="{{ asset('/images/heart_logo_pink.png') }}" alt="いいね済み" class="like-btn__img">
                            @else
                            <img src="{{ asset('/images/heart_logo.png') }}" alt="いいね" class="like-btn__img">
                            @endif
                        </button>
                    </form>
                    <p class="like-count" data-testid="like-count">{{ $likesCount }}</p>
                </div>
                <div class="item-comments">
                    <p class="comment-icon">
                        <img src="{{ asset('/images/comment_logo.png') }}" alt="ふきだしロゴ">
                    </p>
                    <p class="comment-count" data-testid="comment-count">{{ $item->comments->count() }}</p>
                </div>
            </div>
        </div>

        <form action="/purchase/{{ $item->id }}" class="purchase-form" method="get">
            <button class="purchase-btn">購入手続きへ</button>
        </form>

        <div class="item-description">
            <h4>商品説明</h4>
            <p class="description">{{ $item->description }}</p>
        </div>
        <div class="item-info">
            <h4>商品の情報</h4>
            <table class="info-table">
                <tr class="table-row">
                    <th class="table-header">カテゴリー</th>
                    @foreach ($item->categories as $category)
                    <td class="item-category">{{ $category->category }}</td>
                    @endforeach
                </tr>
                <tr class="table-row">
                    <th class="table-header">商品の状態</th>
                    <td class="item-condition">{{ $item->condition->condition }}</td>
                </tr>
            </table>
        </div>

        <div class="comment-content">
            <h4>コメント({{ $item->comments->count() }})</h4>
            <div class="item-comment__list">
                @foreach ($item->comments as $comment)
                <div class="comment-item">
                    <div class="comment-user-content">
                        <img src="{{ asset('storage/'.$comment->user->profile->profile_image) }}" alt="ユーザーアイコン">
                        <p class="comment-user">{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment-body">
                        {{ $comment->comment }}
                    </div>
                </div>
                @endforeach
            </div>
            <form action="/item/{{ $item->id }}/comment" class="comment-form" method="post">
                @csrf
                <div class="form-group">
                    <label for="comment">商品へのコメント</label>
                    <textarea name="comment" id="comment"></textarea>
                    @error('comment')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <button class="comment-btn__submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection