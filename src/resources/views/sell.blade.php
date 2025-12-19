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
        <h3>商品の出品</h3>
    </div>
    <form action="/sell" class="sell-form" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="item-img">
            <div class="image_output" id="list">
                <img src="{{ asset('storage/' . $item->item_image) }}" class="reader_image">
                <label for="item_image" class="image-label">画像を選択する</label>
                <input type="file" id="item_image" class="image" name="item_image" hidden>
            </div>
            <div class="form__error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="item-detail">
            <h4>商品の詳細</h4>
            <div class="form__group">
                <label class="form__group-label">カテゴリー</label>
                @foreach($categories as $category)
                <label>
                    <input type="checkbox" value="{{ $category->id }}" name="category_item[]">{{ $category->category }}
                </label>
                @endforeach
                <div class="form__error">
                    @error('category')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <p class="condition-label">商品の状態</p>
                <div class="custom-select" id="conditionSelect">
                    <div class="selected" id="selectedCondition">選択してください</div>
                    <ul class="options">
                        @foreach($conditions as $condition)
                        <li data-value="{{$condition->id}}">{{$$condition->condition}}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="condition_id" id="condition">
                <div class="form__error">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="item-description">
            <h4>商品名と説明</h4>
            <div class="form__group">
                <label class="form__group-label">商品名</label>
                <input type="text" class="form__group-input" name="name" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label class="form__group-label">ブランド名</label>
                <input type="text" class="form__group-input" name="brand_name" value="{{ old('brand_name') }}">
            </div>
            <div class="form__group">
                <label class="form__group-label">商品の説明</label>
                <textarea clos="30" row="5" name="description" id="description">{{ old('description') }}</textarea>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label class="form_group-label">販売価格</label>
                <input type="text" class="form__group-input" name="price" value="{{ old('price') }}">
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
    document.getElementById('item_image').onchange = function (event) {
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

<script>
    const select = document.getElementById('conditionSelect');
    const selected = document.getElementById('selectedCondition');
    const options = document.querySelectorAll('.options li');
    const hiddenInput = document.getElementById('condition');
    const initialPayment = "{{ $condition }}";

    if (initialCondition) {
        const option = document.querySelector(
            `.options li[data-value="${initialCondition}"]`
        );
        if (option) {
            document.getElementById('selectedCondition').innerText = option.textContent;
            document.getElementById('condition').value = initialPayment;
            document.getElementById('condition_display').innerText = option.textContent;
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
        });
    });
</script>
@endsection