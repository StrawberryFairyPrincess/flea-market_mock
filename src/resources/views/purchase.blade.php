@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')

<div class="purchase">

    {{-- 画面左部 --}}
    <div class="content">

        <div class="item">

            {{-- 商品画像 --}}
            <div class="item-img">
                @if(strpos($item['img_url'],'http') === false)
                    @if(is_null($item['img_url']) === false)
                        {{-- アップロード画像表示 --}}
                        <img src="{{ asset('storage/' . $item['img_url']) }}" alt="商品画像">
                    @endif
                @else
                    {{-- seedingファイルURL表示 --}}
                    <img src="{{ $item['img_url'] }}" alt="商品画像">
                @endif
            </div>

            <div class="item-content">

                {{-- 商品名 --}}
                <span class="item-name">{{ $item['name'] }}</span></br>

                {{-- 価格 --}}
                <?php $price = number_format( $item['price'], 0 ); ?>
                <span class="price">
                    ¥
                    <span class="price-number">{{ $price }}</span>
                </span></br>

            </div>

        </div>

        <div class="payment">
            <span class="ttl">支払い方法</span>

            <form action="{{ '/purchase/' . $item['id'] }}" method="POST">
                @csrf
            <div class="select_wrapper">
                <select name="payment" id="select" onChange="updateDisplay()">
                    <option value="">支払い方法を選択してください</option>
                    <option value="convenience">コンビニ払い</option>
                    <option value="credit">カード払い</option>
                </select>
            </div>

        </div>

        <div class="destination">
            <span class="ttl">配送先</span>
            <div class="destination-change">
                <a href="{{ '/purchase/address/' . $item['id'] }}">変更する</a>
            </div>

            <div class="address">
                〒<input type="text" name="post_code" value="{{ $user->destination->post_code }}" readonly /></br>
                <input type="text" name="address" value="{{ $user->destination->address }}" readonly />
                <input type="text" name="building" value="{{ $user->destination->building }}" readonly />
            </div>

        </div>

    </div>

    {{-- 画面右部 --}}
    <div class="display">

        {{-- 商品代金の表示 --}}
        <div class="display-price">
            <div class="display-ttl">商品代金</div>
            <div class="display-price-num">
                <span class="display-price-symbol">¥</span>{{ $price }}
            </div>

        </div>

        {{-- 選択した支払い方法の表示 --}}
        <div class="display-payment">
            <div class="display-ttl">支払い方法</div>
            <div id="displayArea"></div>
            <script>
                function updateDisplay() {
                    var selectedValue = document.getElementById("select").value;
                    // document.getElementById("displayArea").textContent = selectedValue;
                    // document.getElementById("displayArea").textContent = select.selectedIndex;
                    const num = select.selectedIndex;
                    document.getElementById("displayArea").textContent = select.options[num].innerText;
                }
            </script>
        </div>

        {{-- 購入ボタン --}}
        @if( $sold )
            <button class="sold" type="button" disabled>完売しました</button>
        @else
            <button class="purchase-btn" type="submit">購入する</button></form>
        @endif

        {{-- バリデーションエラーメッセージ --}}
        <div class="form__error">
            @error('payment')
                {{ $message }}
            @enderror
            @error('address')
                {{ $message }}
            @enderror
        </div>

    </div>

</div>
@endsection