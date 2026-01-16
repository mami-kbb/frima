@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('search')
@include('layouts.search_bar')
@endsection

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="sell-content">
    <div class="sell-title">
        <h2>商品の出品</h2>
    </div>
    <form action="/sell" class="sell-form" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="item-img">
            <p class="item-img__title">商品画像</p>
            <div class="image_output" id="list">
                <label for="item_image" class="image-label">画像を選択する</label>
            </div>
            <input type="file" id="item_image" class="image" name="item_image" hidden>
            <div class="form__error">
                @error('item_image')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="item-detail">
            <h3>商品の詳細</h3>
            <div class="category__group">
                <label class="category__group-label">カテゴリー</label>
                <div class="category-list">
                    @foreach($categories as $category)
                    <input type="checkbox" id="category_{{ $category->id }}" value="{{ $category->id }}" {{ in_array($category->id, old('category_item', [])) ? 'checked' : '' }} name="category_item[]" class="category-checkbox">
                    <label for="category_{{ $category->id }}" class="category-btn">
                    {{ $category->category }}
                    </label>
                    @endforeach
                </div>
                <div class="form__error">
                    @error('category_item')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="condition__group">
                <p class="condition-label">商品の状態</p>
                <div class="custom-select" id="conditionSelect">
                    <div class="selected" id="selectedCondition">選択してください</div>
                    <ul class="options">
                        @foreach($conditions as $condition)
                        <li data-value="{{$condition->id}}">{{ $condition->condition}}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="condition_id" id="condition">
                <div class="form__error">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="item-description">
            <h3>商品名と説明</h3>
            <div class="form__group">
                <label for="name" class="form__group-label">商品名</label>
                <input id="name" type="text" class="form-name__input" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label for="brand_name" class="form__group-label">ブランド名</label>
                <input id ="brand_name" type="text" class="form-brand__input" name="brand_name" value="{{ old('brand_name') }}">
            </div>
            <div class="form__group">
                <label class="form__group-label">商品の説明</label>
                <textarea class="description" cols="30" rows="5" name="description" id="description">{{ old('description') }}</textarea>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label for="price" class="form__group-label">販売価格</label>
                <div class="price-input">
                    <span class="price-yen">&yen;</span>
                    <input id="price" type="text" class="form-price__input" name="price" value="{{ old('price') }}">
                </div>
                <div class="form__error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__btn">
            <button class="form__btn-submit" type="submit">出品する</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('item_image').addEventListener('change', function (e) {
        const list = document.getElementById('list');

        const file = e.target.files[0];
        if (!file) return;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'reader_image';

        list.innerHTML = '';
        list.appendChild(img);
    });
</script>

<script>
    const select = document.getElementById('conditionSelect');
    const selected = document.getElementById('selectedCondition');
    const options = document.querySelectorAll('.options li');
    const hiddenInput = document.getElementById('condition');
    const initialCondition = "{{ old('condition_id') }}";

    if (initialCondition) {
        const option = document.querySelector(
            `.options li[data-value="${initialCondition}"]`
        );

        if (option) {
            selected.textContent = option.textContent;
            hiddenInput.value = initialCondition;
        }
    }

    select.addEventListener('click', (e) => {
        if (e.target.closest('.options'))return;

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
        });
    });
</script>
@endsection