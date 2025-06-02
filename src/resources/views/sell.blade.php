@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
@endsection

@section('content')
<div class="form__content">

    <div class="form__heading">
        <h2>商品の出品</h2>
    </div>

    <form class="form" action="/sell" method="POST"  enctype="multipart/form-data">
        @csrf

        {{-- 画像選択 --}}
        <div class="form__group-title">商品画像</div>

        <div class="img">
            <label class="select-img">
                画像を選択する
                <input type="file" name="img_url" accept="image/*"/>
            </label>
        </div>
        {{-- バリデーションエラーメッセージ --}}
        <div class="form__error">
            @error('img_url')
                {{ $message }}
            @enderror
        </div>

        {{-- 選択肢部分 --}}
        <div class="ttl">商品の詳細</div>

        {{-- チェックボックス --}}
        <div class="form__group">
            <div class="form__group-title">カテゴリー</div>

            <div class="form__group-content">

                <div class="category">
                    @foreach($categories as $category)
                        <label>
                            <input type="checkbox" name="category_id[]" value="{{ $category['id'] }}"
                                {{ is_array(old('category_id')) && in_array($category['id'], old('category_id')) ?
                                'checked=checked' : '' }} >
                            <span>{{ $category['category'] }}</span>
                        </label>

                        @if($loop->iteration % 6 == 0)
                            </br></br>
                        @endif
                    @endforeach
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('category_id')
                        {{ $message }}
                    @enderror
                </div>

            </div>
        </div>

        {{-- セレクトボタン --}}
        <div class="form__group">
            <div class="form__group-title">商品の状態</div>

            <div class="form__group-content">

                <div class="select_wrapper">
                    <select name="condition_id">

                        <option value="" selected>選択してください</option>

                        @foreach($conditions as $condition)
                            <option value="{{ $condition['id'] }}"
                                @if( old('condition_id') == $condition['id'] ) selected @endif>
                                {{ $condition['condition'] }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('condition_id')
                        {{ $message }}
                    @enderror
                </div>

            </div>
        </div>

        {{-- 入力部分 --}}
        <div class="ttl">商品名と説明</div>

        <div class="form__group">

            <div class="form__group-title">
                <span class="form__label--item">商品名</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ブランド名</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="brand" value="{{ old('brand') }}" />
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の説明</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <textarea name="describe">{{ old('describe') }}</textarea>
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('describe')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">販売価格</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="price" placeholder="¥" value="{{ old('price') }}" />
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('price')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>

</div>
@endsection