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
    <form action="/mypage/profile" class="profile-form" method="post" novalidate>
        @csrf
        <div class="top-content">
            <output id="list" class="image_output"></output>
            <input type="file" id="profile_image" class="image" name="profile_image" placeholder="画像を選択する">
            @error('image')
            <span class="error">
                <p class="error_message">{{ $message }}</p>
            </span>
            @enderror
        </div>
        <div class="under-content">
            <div class="form__group">
                <div class="form__group-item">
                    <label for="name" class="form__group-label">ユーザー名</label>
                    <input type="text" class="form__group-input" name="name" value="{{ old('name') }}">
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
                    <input type="text" class="form__group-input" name="postal_code" value="{{ old('postal_code') }}">
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
                    <input type="text" class="form__group-input" name="address" value="{{ old('address') }}">
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
                    <input type="text" class="form__group-input" name="building" value="{{ old('building') }}">
                </div>
            </div>
            <div class="form__btn">
                <button class="form__btn-submit" type="submit">更新する</button>
            </div>
        </div>
    </form>
</div>
<script>
    document.getElementById('profile_image').onchange = function(event){

        initializeFiles();

        var files = event.target.files;

        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader;
            reader.readAsDataURL(f);

            reader.onload = (function(theFile) {
                return function (e) {
                    var div = document.createElement('div');
                    div.className = 'reader_file';
                    div.innerHTML += '<img class="reader_image" src="' + e.target.result + '" />';
                    document.getElementById('list').insertBefore(div, null);
                }
            })(f);
        }
    };

    function initializeFiles() {
        document.getElementById('list').innerHTML = '';
    }

</script>
@endsection