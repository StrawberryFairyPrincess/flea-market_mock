@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}" />
@endsection

@section('content')
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

    {{-- 商品説明 --}}
    <div class="item-content">

        {{-- 商品名 --}}
        <span class="item-name">{{ $item['name'] }}</span></br>

        {{-- ブランド名 --}}
        <span class="item-brand">{{ $item['brand'] }}</span></br>

        {{-- 価格 --}}
        <?php $price = number_format( $item['price'], 0 ); ?>
        <span class="price">
            ¥
            <span class="price-number">{{ $price }}</span>
            (税込)
        </span></br>

        {{-- アイコン表示 --}}
        <div class="icon">
            <div class="like">
                <?php if(\Auth::check()){ ?>
                    @if($item->is_liked_by_auth_user())
                        <a href="{{ route('item.dislike', ['item_id' => $item->id]) }}">
                            <img src="{{ asset('./img/icon/like.png') }}" alt="like"></br>
                            <span class="badge">{{ $item->likes->count() }}</span>
                        </a>
                    @else
                        <a href="{{ route('item.like', ['item_id' => $item->id]) }}">
                            <img src="{{ asset('./img/icon/dislike.png') }}" alt="dislike"></br>
                            <span class="badge">{{ $item->likes->count() }}</span>
                        </a>
                    @endif
                <?php } else { ?>
                    <img src="{{ asset('./img/icon/dislike.png') }}" alt="dislike"></br>
                    <span class="badge">{{ $item->likes->count() }}</span>
                <?php } ?>
            </div>
            <div class="comment">
                <img src="{{ asset('./img/icon/comment.png') }}" alt="comment">

                <span class="badge">
                    
                </span>
            </div>
        </div>


    </div>

</div>
@endsection