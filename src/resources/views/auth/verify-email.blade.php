{{-- ログイン画面用ビューファイル --}}

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')

<div class="flex"><div class="content">

    <div class="text">
        登録していただいたメールアドレスに認証メールを送付しました。</br>
        メール認証を完了してください。
    </div>

    <div class="verify">
        <form action="　　　" method="　　　">
            @csrf

            <div class="form__button">
                <button class="form__button-submit" type="button" onclick="location.href='http://localhost:8025/'">承認はこちらから</button>
            </div>
        </form>
    </div>

    <div class="mail">
        <form action="/email/verification-notification" method="POST">
            @csrf

            <div class="form__button">
                <button type="submit">承認メールを再送する</button>
            </div>
        </form>
    </div>
</div></div>
@endsection
