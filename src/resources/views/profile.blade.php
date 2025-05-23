@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="form__content">

    <div class="form__heading">
        <h2>プロフィール設定</h2>
    </div>

    <div class="img-set">
        <div class="profile-img">
            @if( $destination['img_pass'] != NULL )
                <img src="{{ asset('storage/' . $destination['img_pass']) }}" alt="プロフィール画像">
            @endif
        </div>
    </div>

    <form class="form" action="/mypage/profile" method="post"  enctype="multipart/form-data">
        @csrf

        <label class="select-img">
            画像を選択する
            <input type="file" name="img_pass" accept="image/*"/>

            {{-- バリデーションエラーメッセージ --}}
            <div class="form__error">
                @error('img_pass')
                    {{ $message }}
                @enderror
            </div>
        </label>

        <div class="form__group">

            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ $user['name'] }}" />
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