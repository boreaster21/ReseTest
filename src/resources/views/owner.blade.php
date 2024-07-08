@extends('layouts.app')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/owner.css') }}">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="{{ asset('js/favs.js') }}"></script>
@endsection

@section('nav')
@endsection

@section('content')
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
<div class="content">
    <div>
        <h2>店舗情報の作成</h2>
    </div>
    <div class="form">
        <form action="/owner" class="form" method="post" enctype="multipart/form-data">
            @csrf
            <table>
                <tr>
                    <th>店名：</th>
                    <td><input type="text" name="name" class="input" value="{{ old('name') }}" placeholder="Reseレストラン" required></td>
                </tr>
                <tr>
                    <th>地域：</th>
                    <td><select name="area" class="input" value="{{ old('name') }}" required>
                            <option value="東京都">東京都</option>
                            <option value="大阪府">大阪府</option>
                            <option value="福岡県">福岡県</option>
                        </select></td>
                </tr>
                <tr>
                    <th>ジャンル：</th>
                    <td><select name="ganre" class="input" value="{{ old('name') }}" required>
                            <option value="寿司">寿司</option>
                            <option value="焼き肉">焼き肉</option>
                            <option value="居酒屋">居酒屋</option>
                            <option value="イタリアン">イタリアン</option>
                            <option value="ラーメン">ラーメン</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>詳細説明：</th>
                    <td><textarea name="detail" cols="30" rows="3" required>本格イタリアンをお楽しみ頂けます。</textarea></td>
                </tr>
                <tr>
                    <th>画像：</th>
                    <td><input type="file" name="image" class="input" required></td>
                </tr>
                <tr>
                    <th><button type="submit">出店する</button></th>
                </tr>
            </table>
        </form>
    </div>

    <div>
        <h2>店舗情報の更新</h2>
    </div>
    <div class="wrap">
        @foreach ($shops as $shop)
        <div class="item">
            <div class="card__img">
                <img src="{{Storage::url($shop->URL)}}" alt="{{$shop->name}}" />
            </div>
            <div class="card__content">
                <div class="card__name">{{$shop->name}}</div>
                <div class="tag">
                    <p class="card__tag">#{{$shop->area}}</p>
                    <p class="card__tag">#{{$shop->ganre}}</p>
                </div>
                <div class="detail__button">
                    <a href="/owner/edit/{{$shop->id}}" class="button__detail">編集する
                    </a>
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
    <div>
        <h2>店舗情報の予約確認</h2>
    </div>
    @foreach ($bookings as $booking)
    @if($booking->visited != 1)
    <div class="booking-cards">
        <p class="booking-cards__number">予約{{$booking_No++ }}</p>
        <!-- <a href="{{ route('booking.unbook', ['id' => $booking->id])}}">
            <img class='batu' src="{{ asset('img/batu.png') }}" alt="予約解除">
        </a>
        <a href="{{ route('booking.edit', ['id' => $booking->id])}}">
            <img class='batu' src="{{ asset('img/edit.png') }}" alt="予約編集">
        </a> -->
        <table class="booking-detail">
            <form action="{{ route('booking.edit') }}" class="booking__detail" method="post">
                @csrf
                <tr>
                    <th>SHOP</th>
                    @foreach ( $shops as $shop )
                    @if ( $shop->id == $booking->shop_id )
                    <td>{{ $shop->name }}</td>
                    @endif
                    @endforeach
                </tr>
                <tr>
                    <th>Name</th>
                    @foreach ( $users as $user )
                    @if ( $user->id == $booking->user_id )
                    <td>{{ $user->name }}様</td>
                    @endif
                    @endforeach
                    <!-- <td>　→　</td>
                    <td><input type="date" name="new_date" class="date" value="{{ $booking->date }}"></td> -->
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $booking->date }}</td>
                    <!-- <td>　→　</td>
                    <td><input type="date" name="new_date" class="date" value="{{ $booking->date }}"></td> -->
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{{ $booking->time }}</td>
                    <!-- <td>　→　</td>
                    <td>
                        <select name="new_time" class="time" value="{{ old('time') }}">
                            <option value="{{ $booking->time }}">{{ $booking->time }}</option>
                            @for ($i = 0; $i < 13; $i++) <option value="{{$i+9}}:00">{{$i+9}}:00</option>
                                @endfor
                        </select>
                    </td> -->
                </tr>
                <tr>
                    <th>Number</th>
                    <td>{{ $booking->num }}名様</td>
                    <!-- <td>　→　</td>
                    <td>
                        <select name="new_num" class="num" value="{{ old('num') }}">
                            <option value="{{ $booking->num }}">{{ $booking->num }}</option>
                            @for ($i = 1; $i < 11; $i++) <option value="{{$i}}">{{$i}}人</option>
                                @endfor
                        </select>
                    </td> -->
                    <!-- <td><button>予約内容変更</button></td> -->
                </tr>
                <input type="hidden" name="shop_id" value="{{$shop->id}}">
                <input type="hidden" name="booking_id" value="{{$booking->id}}">
            </form>
            <form action="/mail" method="post">
                @csrf
                <tr>
                    <th>利用者へのメッセージ</th>
                    <td><textarea name="message" value="{{ old('message') }}"></textarea></td>
                    @foreach ( $users as $user )
                    @if ( $user->id == $booking->user_id )
                    <td><input type="hidden" name='name' value="{{$user->name}}"></td>
                    @endif
                    @endforeach
                    <td><input type="submit" value="送信" class="btn"></td>
                </tr>
            </form>
        </table>
        @if ($errors->has('message'))
        <p class="bg-danger">{{ $errors->first('message') }}</p>
        @endif
        @if (Session::has('succsss'))
        <div>
            <p class="bg-warning text-center">{{ Session::get('success')}}</p>
        </div>
        @endif
    </div>
    @endif
    @endforeach
</div>
@endsection