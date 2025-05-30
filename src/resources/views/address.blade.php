@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}" />
@endsection

@section('content')

<div class="form__content">

    <div class="form__heading">
        <h2>住所の変更</h2>
    </div>

    <form class="form" action="{{ '/purchase/address/' . $item_id }}" method="POST">
        @csrf

        {{-- DestinationRequestのバリデーション対策 --}}
        <input type="hidden" name="name" value="{{ \Auth::user()->name }}">

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="post_code" value="{{ $destination['post_code'] }}" />
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('post_code')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ $destination['address'] }}" />
                </div>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ $destination['building'] }}" />
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>

</div>
@endsection