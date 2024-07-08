@extends('layouts.app')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/allshop.css') }}">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="{{ asset('js/favs.js') }}"></script>
@endsection

@section('nav')
<nav class="header__nav">
    <div class="search">
        <form class="search-form" action="/" method="post">
            @csrf
            <div class="search-form_item">
                <input class="search-form__item-input" type="text" name="keyword" placeholder="キーワードを入力" value="{{ old('keyword') }}">
            </div>
            <div class="search-form_item">
                <select class="search-form_item-select" name="area" id="">
                    <option class="search-form_item-option" disabled selected>エリア</option>
                    @foreach ($areas as $area)
                    <option class="search-form_item-option">{{$area->area}}</option>
                    @endforeach
                </select>
                <select class="search-form_item-select" name="ganre" id="">
                    <option class="search-form_item-option" disabled selected>ジャンル</option>
                    @foreach ($ganres as $ganre)
                    <option class="search-form_item-option" value="{{$ganre->ganre}}">{{$ganre->ganre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="search-form__button">
                <button class="search-form__button-submit" type="submit" aria-label="検索">検索</button>
            </div>
        </form>
    </div>
</nav>
@endsection

@section('content')
<div class="content">
    @if (session('message'))
    <div class="todo__alert--success">{{ session('message') }}</div>
    @endif @if ($errors->any())
    <div class="todo__alert--danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="wrap">
        @foreach ($shops as $shop)
        <div class="item">
            <div class="card__img">
                <img src="{{$shop->URL}}" alt="{{$shop->name}}" />
            </div>
            <div class="card__content">
                <div class="card__name">{{$shop->name}}</div>
                <div class="tag">
                    <p class="card__tag">#{{$shop->area}}</p>
                    <p class="card__tag">#{{$shop->ganre}}</p>
                </div>
                <div class="detail__button">
                    <a href="/detail/{{$shop->id}}" class="button__detail">詳しく見る</a>
                </div>
                <div class="fav__button">
                    @if($shop->is_faved_by_auth_user())
                    <a href="{{ route('shop.unfav', ['id' => $shop->id]) }}">
                        <img class='heart_black' src="{{ asset('img/heart.png') }}" alt="お気に入り解除">
                        <span class="badge">{{ $shop->favs->count() }}</span>
                    </a>
                    @else
                    <a href="{{ route('shop.fav', ['id' => $shop->id]) }}">
                        <img class='heart' src="{{ asset('img/heartblack.png') }}" alt="お気に入り">
                        <span class="badge">{{ $shop->favs->count() }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection