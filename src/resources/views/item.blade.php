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

            {{-- いいね数 --}}
            <div class="like">
                @if( Auth::check() )
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
                @else
                    <img src="{{ asset('./img/icon/dislike.png') }}" alt="dislike"></br>
                    <span class="badge">{{ $item->likes->count() }}</span>
                @endif
            </div>

            {{-- コメント数 --}}
            <div class="comment-count">
                <img src="{{ asset('./img/icon/comment.png') }}" alt="comment"></br>
                <span class="badge">{{ $item->comments->count() }}</span>
            </div>
        </div>

        {{-- 購入手続きボタン --}}
        <div class="purchase">
            <form  class="purchase-btn" action="{{ '/purchase/' . $item['id'] }}" method="GET">
                @csrf

                @if( $sold )
                    <button class="sold" type="button" disabled>完売しました</button>
                @else
                    <button type="submit">購入手続きへ</button>
                @endif
            </form>
        </div>

        {{-- 商品説明 --}}
        <div class="detail">
            <h2 class="ttl">商品説明</h2>
            <span>{{ $item['describe'] }}</span>
        </div>

        {{-- 商品分類 --}}
        <div class="classification">
            <h2 class="ttl">商品の情報</h2>

            <div class="category">
                <div class="sub-ttl">
                    <span>カテゴリー</span>
                </div>

                <div class="category-elements">
                @foreach( $categories as $category )
                    <div class="category-element">
                        <span class="element">{{ $category['category'] }}</span>
                    </div>
                @endforeach
                </div>

            </div>

            <div class="condition">
                <div class="sub-ttl">
                    <span>商品の状態</span>
                </div>

                <div class="condition-element">
                    <span class="element">{{ $condition }}</span>
                </div>
            </div>
        </div>

        {{-- コメント機能 --}}
        <div class="comment">
            <h2 class="ttl">コメント( {{ $item->comments->count() }} )</h2>

            <div class="comment-list">
                @foreach ($item->comments as $comment)
                    <div class="comment-profile">

                        {{-- コメント記入者のプロフィール画像 --}}
                        <div class="profile-img">
                            {{-- @if( $comment->user->destination->img_pass != NULL ) --}}
                            @if(! empty($comment->user->destination->img_pass) )
                                <img src="{{ asset('storage/' . $comment->user->destination->img_pass) }}"
                                    alt="プロフィール画像">
                            @endif
                        </div>

                        {{-- コメント記入者の名前 --}}
                        <div class="profile-name">
                            <span>{{ $comment['user']['name'] }}</span>
                        </div>

                    </div>

                    <div class="comment-detail">
                        <span>{{ $comment->comment }}</span>
                    </div>
                @endforeach
            </div>

            <form class="comment-btn" action="{{ '/comment/' . $item['id'] }}" method="POST"
                accept-charset="UTF-8" data-remote="true">
                @csrf

                <span>商品へのコメント</span></br>
                <textarea name="comment">{{ old('comment') }}</textarea>

                {{-- バリデーションエラーメッセージ --}}
                <div class="form__error">
                    @error('comment')
                        {{ $message }}
                    @enderror
                </div>

                <button type="submit">コメントを送信する</button>
            </form>

        </div>

    </div>

</div>
@endsection