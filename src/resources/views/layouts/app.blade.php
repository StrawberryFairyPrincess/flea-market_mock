<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FleaMarket</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>

    <header class="header">

        <div class="header__inner">

            <div class="logo">
                <img src="{{ asset('./img/logo.svg') }}" alt="coachtech">
            </div>

            <?php if ( Auth::check() ){ ?>

                <div class="search">
                    <form class="search-form" action="/search" method="GET">
                        @csrf
                        <input class="search-form__item-input" type="text" name="keyword" value="{{ old('keyword') }}" placeholder="なにをお探しですか？">
                    </form>
                </div>

                <nav>
                    <ul class="header-nav">
                        <li class="header-nav__item">
                            <form class="header-nav__button" action="/logout" method="POST">
                                @csrf
                                <button class="logout" type="submit">ログアウト</button>
                            </form>
                        </li>
                        <li class="header-nav__item">
                            <form class="header-nav__button" action="/mypage" method="GET">
                                @csrf
                                <button class="mypage" type="submit">マイページ</button>
                            </form>
                        </li>
                        <li class="header-nav__item">
                            <form class="header-nav__button" action="/sell" method="GET">
                                @csrf
                                <button class="sell" type="submit">出品</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            <?php } ?>

        </div>

    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>