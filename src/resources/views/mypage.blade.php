@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section('content')

<div class="user">
    <div class="img-set">
        <div class="profile-img">
            @if( $user->destination['img_pass'] != NULL )
                <img src="{{ asset('storage/' . $user->destination['img_pass']) }}" alt="プロフィール画像">
            @endif
        </div>

        <div class="user-name">{{ $user['name'] }}</div>
    </div>

    <button class="link" type="button" onclick="location.href='/mypage/profile'">プロフィールを編集</button>
</div>

<div class="tab">
    <?php $path = $_SERVER['REQUEST_URI']; ?>
    @if( strpos($path, '/mypage?tab=buy') !== false )
        <a href="/mypage?tab=sell"><span>出品した商品</span></a>
        <a href="/mypage?tab=buy"><span class="checked">購入した商品</span></a>
    @else
        <a href="/mypage?tab=sell"><span class="checked">出品した商品</span></a>
        <a href="/mypage?tab=buy"><span>購入した商品</span></a>
    @endif
</div>

<div class="content">

    {{-- 商品リスト --}}
    <div class="items">

        {{-- ItemControllerで取得したitemsテーブルのひとつの商品を表示 --}}
        @foreach( $items as $item )
            <div class="item"><a href="{{ './item/' . $item['id'] }}">

                {{-- 商品画像 --}}
                <div class="item-img">
                    @if( strpos($item['img_url'],'http') === false )
                        @if( is_null($item['img_url']) === false )
                            {{-- アップロード画像表示 --}}
                            <img src="{{ asset('storage/' . $item['img_url']) }}" alt="商品画像">
                        @endif
                    @else
                        {{-- seedingファイルURL表示 --}}
                        <img src="{{ $item['img_url'] }}" alt="商品画像">
                    @endif

                    @if( isset($item->purchase['item_id']) )
                        <div class="mask-sold">
                            <div class="sold">
                                <p>SOLD</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 商品名 --}}
                <div class="name">
                    <span>{{ $item['name'] }}</span>
                </div>

            </a></div>
        @endforeach

    </div>
</div>


@endsection