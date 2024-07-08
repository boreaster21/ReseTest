<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">

            <input type="checkbox" id="toggle-nav">
            <label for="toggle-nav" class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </label>
            <div class="menu">
                <ul>
                    <li class="gnav__menu__item"><a href="/">HOME</a></li>
                    @guest
                    <li class="gnav__menu__item"><a href="{{ route('register') }}">Registration</a></li>
                    <li class="gnav__menu__item"><a href="{{ route('login') }}">LOGIN</a></li>
                    @endguest
                    @auth
                    <li class="gnav__menu__item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="button-logout">LOGOUT</button>
                        </form>
                    </li>
                    <li class="gnav__menu__item"><a href="/mypage">Mypage</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <a class="header__logo" href="/">Rese</a>
            </div>
            @yield('nav')
    </header>

    <main class='main'>
        @yield('content')
    </main>
    <footer class="footer">
        <div class="footer__inner">
            <a href="/" class="footer__logo">Rese, inc.</a>
        </div>
    </footer>
</body>

</html>