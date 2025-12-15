@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="profile-form__content">
    <div class="profile-form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form action="/mypage/profile" class="profile-form" method="post" enctype="multipart/form-data" novalidate>
        @method('patch')
        @csrf
        <div class="top-content">
            <div class="image_output" id="list">
                @if ($profile && $profile->profile_image)
                <img src="{{ asset('storage/' . $profile->profile_image) }}" class="reader_image">
            @endif
            </div>
            <label for="profile_image" class="image-label">画像を選択する</label>
            <input type="file" id="profile_image" class="image" name="profile_image" hidden>
            @error('image')
            <span class="error">
                <p class="form__error">{{ $message }}</p>
            </span>
            @enderror
        </div>
        <div class="under-content">
            <div class="form__group">
                <div class="form__group-item">
                    <label for="name" class="form__group-label">ユーザー名</label>
                    <input type="text" class="form__group-input" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-item">
                    <label for="postal_code" class="form__group-label">郵便番号</label>
                    <input type="text" class="form__group-input" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                </div>
                <div class="form__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-item">
                    <label for="address" class="form__group-label">住所</label>
                    <input type="text" class="form__group-input" name="address" value="{{ old('address', $profile->address ?? '') }}">
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-item">
                    <label for="building" class="form__group-label">建物名</label>
                    <input type="text" class="form__group-input" name="building" value="{{ old('building', $profile->building ?? '') }}">
                </div>
            </div>
            <div class="form__btn">
                <button class="form__btn-submit" type="submit">更新する</button>
            </div>
        </div>
    </form>
</div>
<script>
    document.getElementById('profile_image').onchange = function (event) {
    const list = document.getElementById('list');
    list.innerHTML = '';

    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'reader_image';
        list.appendChild(img);
    };
    reader.readAsDataURL(file);
};
</script>
@endsection