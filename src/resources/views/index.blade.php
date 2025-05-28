@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')

<div class="tab">
    {{-- ログイン時表示 --}}
    @if(Auth::check())
        <?php $path = $_SERVER['REQUEST_URI']; ?>
        @if( strpos($path, '/?tab=mylist') !== false )
            <a href="./"><span>おすすめ</span></a>
            <a href="./?tab=mylist"><span class="checked">マイリスト</span></a>
        @elseif( strpos($path, '/') !== false )
            <a href="./"><span class="checked">おすすめ</span></a>
            <a href="./?tab=mylist"><span>マイリスト</span></a>
        @endif
    {{-- 未ログイン時表示 --}}
    @else
        <a href="./"><span class="checked">おすすめ</span></a>
        <span>マイリスト</span>
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