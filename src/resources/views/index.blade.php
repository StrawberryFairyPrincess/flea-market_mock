@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')

<div class="tabbox">
    {{-- ログイン時表示 --}}
    @if(Auth::check())
        <?php $path = $_SERVER['REQUEST_URI']; ?>
        @if( strpos($path, '/') !== false )
            <input type="radio" name="tabset" id="tabcheck1" checked>
            <label for="tabcheck1" class="tab"><a href="./"><span>おすすめ</span></a></label>
            <input type="radio" name="tabset" id="tabcheck2">
            <label for="tabcheck2" class="tab"><a href="./?tab=mylist"><span>マイリスト</span></a></label>
        @else if( strpos($path, '/mylist') !== false )
            <input type="radio" name="tabset" id="tabcheck1">
            <label for="tabcheck1" class="tab"><a href="./"><span>おすすめ</span></a></label>
            <input type="radio" name="tabset" id="tabcheck2" checked>
            <label for="tabcheck2" class="tab"><a href="./?tab=mylist"><span>マイリスト</span></a></label>
        @endif
    {{-- 未ログイン時表示 --}}
    @else
        <input type="radio" name="tabset" id="tabcheck1" checked >
        <label for="tabcheck1" class="tab"><a href="./"><span>おすすめ</span></a></label>
        <input type="radio" name="tabset" id="tabcheck2">
        <span>マイリスト</span>
    @endif

</div>

<div class="content">

    {{-- 商品リスト --}}
    <div class="items">

        {{-- ItemControllerで取得したitemsテーブルのひとつの商品を表示 --}}
        @foreach($items as $item)
            <div class="item"><a href="./item/:$item[id]">

                {{-- 商品画像 --}}
                <div class="item-img">
                    @if(strpos($item['img_url'],'http') === false)
                        {{-- アップロード画像表示 --}}
                        {{-- <img src="{{ asset('storage/' . $item['img_url']) }}" alt="商品画像"> --}}
                    @else
                        {{-- seedingファイルURL表示 --}}
                        <img src="{{ $item['img_url'] }}" alt="商品画像">
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