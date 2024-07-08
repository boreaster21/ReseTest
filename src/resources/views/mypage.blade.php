@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('nav')
<nav class="header__nav">
    <ul class="nav__list">
        @can ('admin')
        <li class="nav__list-item"><a href="/admin">店舗代表者管理</a></li>
        @endcan
        @can ('owner')
        <li class="nav__list-item"><a href="/owner">店舗管理</a></li>
        @endcan
        <!-- <li class="nav__list-item"><a href="/OwnerRequest">出店申請</a></li>
        <li class="nav__list-item"><a href="/date">全体勤怠表</a></li>
        <li class="nav__list-item"><a href="/users">ユーザー一覧</a></li>
        <li class="nav__list-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="button-logout">ログアウト</button>
            </form>
        </li> -->
    </ul>
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
    <div class="booking_fav">
        <div class="booking-state">
            <h2 class="user-name">　</h2>
            <h3 class="ttl">予約状況</h3>
            @foreach ($bookings as $booking)
            @if($booking->visited != 1)
            <div class="booking-cards">
                <p class="booking-cards__number">予約{{$booking_No++ }}</p>
                <a href="{{ route('booking.unbook', ['id' => $booking->id])}}">
                    <img class='batu' src="{{ asset('img/batu.png') }}" alt="予約解除">
                </a>
                <a href="{{ route('booking.edit', ['id' => $booking->id])}}">
                    <img class='batu' src="{{ asset('img/edit.png') }}" alt="予約編集">
                </a>
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
                            <th>Date</th>
                            <td>{{ $booking->date }}</td>
                            <td>　→　</td>
                            <td><input type="date" name="new_date" class="date" value="{{ $booking->date }}"></td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $booking->time }}</td>
                            <td>　→　</td>
                            <td>
                                <select name="new_time" class="time" value="{{ old('time') }}">
                                    <option value="{{ $booking->time }}">{{ $booking->time }}</option>
                                    @for ($i = 0; $i < 13; $i++) <option value="{{$i+9}}:00">{{$i+9}}:00</option>
                                        @endfor
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td>{{ $booking->num }}</td>
                            <td>　→　</td>
                            <td>
                                <select name="new_num" class="num" value="{{ old('num') }}">
                                    <option value="{{ $booking->num }}">{{ $booking->num }}</option>
                                    @for ($i = 1; $i < 11; $i++) <option value="{{$i}}">{{$i}}人</option>
                                        @endfor
                                </select>
                            </td>
                            <td><button>予約内容変更</button></td>
                        </tr>
                        <input type="hidden" name="shop_id" value="{{$shop->id}}">
                        <input type="hidden" name="booking_id" value="{{$booking->id}}">
                    </form>
                </table>
                <form action="{{ route('booking.visited') }}" class="booking__visited" method="post">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <input type="hidden" name="booking_id" value="{{$booking->id}}">
                    <button>訪店完了</button>
                </form>
                <div class="payment">
                    <form action="{{ asset('payment') }}" method="POST">
                        @csrf
                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="{{ env('STRIPE_KEY') }}" data-amount="100" data-name="Stripe決済デモ" data-label="決済をする" data-description="これはデモ決済です" data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-currency="JPY">
                        </script>
                    </form>

                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="favs">
            <h2 class="user-name">{{ Auth::user()->name }} さん</h2>
            <h3 class="ttl">お気に入り店舗</h3>
            <div class="wrap">
                @foreach ($shops as $shop)
                @foreach ($favs as $fav)
                @if ($shop->id == $fav->shop_id)
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
                        <div class="card__button">
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
                @endif
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
    <div class="reviews">
        <div class="not_posted">
            <h3 class="ttl">レビュー投稿</h3>
            <h4>未投稿</h4>
            @foreach ($bookings as $booking)
            @if($booking->visited == 1 && $booking->posted != 1)
            <div class="reviews-cards">
                <div class="reviews-detail">
                    <!-- <p>予約履歴{{$No++ }}</p> -->
                    <table>
                        <tr>
                            <th>SHOP</th>
                            @foreach ( $shops as $shop )
                            @if ( $shop->id == $booking->shop_id )
                            <td>{{ $shop->name }}</td>
                            @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $booking->date }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $booking->time }}</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td>{{ $booking->num }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <form action="{{ route('shop.review') }}" class="reviews__form" method="post">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{$booking->shop_id}}">
                        <input type="hidden" name="booking_id" value="{{$booking->id}}">
                        <div class="stars">
                            <span class="stars-span">
                                <input type="radio" name="stars" value="5"><label for="review01">★</label>
                                <input type="radio" name="stars" value="4"><label for="review02">★</label>
                                <input type="radio" name="stars" value="3"><label for="review03">★</label>
                                <input type="radio" name="stars" value="2"><label for="review04">★</label>
                                <input type="radio" name="stars" value="1"><label for="review05">★</label>
                            </span>
                        </div>
                        <textarea name="comment"></textarea>
                        <button>投稿する</button>
                    </form>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="posted">
            <h3 class="ttl">　</h3>
            <h4>投稿済み</h4>
            @foreach ($bookings as $booking)
            @foreach ($reviews as $review)
            @if($review->shop_id == $booking->shop_id)
            <div class="reviews-cards">
                <div class="reviews-detail">
                    <!-- <p>予約履歴{{$No++ }}</p> -->
                    <table>
                        <tr>
                            <th>SHOP</th>
                            @foreach ( $shops as $shop )
                            @if ( $shop->id == $booking->shop_id )
                            <td>{{ $shop->name }}</td>
                            @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $booking->date }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $booking->time }}</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td>{{ $booking->num }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <form action="{{ route('review.edit') }}" class="reviews__form" method="post">
                        @csrf
                        <div class="stars">
                            <span class="stars-span">
                                @if($review->stars == 1)
                                <input id="review01" type="radio" name="posted_stars" value="5"><label for="review01">★</label>
                                <input id="review02" type="radio" name="posted_stars" value="4"><label for="review02">★</label>
                                <input id="review03" type="radio" name="posted_stars" value="3"><label for="review03">★</label>
                                <input id="review04" type="radio" name="posted_stars" value="2"><label for="review04">★</label>
                                <input id="review05" type="radio" name="posted_stars" value="1" checked><label for="review05">★</label>
                                @elseif ($review->stars == 2)
                                <input id="review01" type="radio" name="posted_stars" value="5"><label for="review01">★</label>
                                <input id="review02" type="radio" name="posted_stars" value="4"><label for="review02">★</label>
                                <input id="review03" type="radio" name="posted_stars" value="3"><label for="review03">★</label>
                                <input id="review04" type="radio" name="posted_stars" value="2" checked><label for="review04">★</label>
                                <input id="review05" type="radio" name="posted_stars" value="1"><label for="review05">★</label>
                                @elseif ($review->stars == 3)
                                <input id="review01" type="radio" name="posted_stars" value="5"><label for="review01">★</label>
                                <input id="review02" type="radio" name="posted_stars" value="4"><label for="review02">★</label>
                                <input id="review03" type="radio" name="posted_stars" value="3" checked><label for="review03">★</label>
                                <input id="review04" type="radio" name="posted_stars" value="2"><label for="review04">★</label>
                                <input id="review05" type="radio" name="posted_stars" value="1"><label for="review05">★</label>
                                @elseif ($review->stars == 4)
                                <input id="review01" type="radio" name="posted_stars" value="5"><label for="review01">★</label>
                                <input id="review02" type="radio" name="posted_stars" value="4" checked><label for="review02">★</label>
                                <input id="review03" type="radio" name="posted_stars" value="3"><label for="review03">★</label>
                                <input id="review04" type="radio" name="posted_stars" value="2"><label for="review04">★</label>
                                <input id="review05" type="radio" name="posted_stars" value="1"><label for="review05">★</label>
                                @elseif ($review->stars == 5)
                                <input id="review01" type="radio" name="posted_stars" value="5" checked><label for="review01">★</label>
                                <input id="review02" type="radio" name="posted_stars" value="4"><label for="review02">★</label>
                                <input id="review03" type="radio" name="posted_stars" value="3"><label for="review03">★</label>
                                <input id="review04" type="radio" name="posted_stars" value="2"><label for="review04">★</label>
                                <input id="review05" type="radio" name="posted_stars" value="1"><label for="review05">★</label>
                                @endif
                            </span>
                        </div>
                        <textarea name="comment" placeholder="{{$review->comment}}"></textarea>
                        <button>編集する</button>
                        <input type="hidden" name="shop_id" value="{{$booking->shop_id}}">
                        <input type="hidden" name="booking_id" value="{{$booking->id}}">
                        <input type="hidden" name="review_id" value="{{$review->id}}">
                    </form>
                </div>
            </div>
            @endif
            @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection