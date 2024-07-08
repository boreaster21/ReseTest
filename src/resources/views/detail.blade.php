@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection


@section('content')
<div class="content">
    <div class="detail">
        <div class="heading">
            <a href="/" class="back">＜</a>
            <h2 class="name">{{$shop->name}}</h2>
        </div>
        <div class="img">
            <img src="{{$shop->URL}}" alt="{{$shop->name}}">
        </div>
        <div class="tag">
            <p class="tag__region">#{{$shop->area}}</p>
            <p class="tag__ganre"> #{{$shop->ganre}}</p>
        </div>
        <div class="detail">
            <p class="detail__text">{{$shop->detail}}</p>
        </div>
    </div>
    <div class="booking">
        <div class="ttl">
            <h2 class="ttl__txt">予約</h2>
        </div>
        <div class="input">
            <form action="/book" class="booking__detail" method="post">
                @csrf
                <input type="hidden" name="user_id" value="">
                <input type="hidden" name="shop_id" value="{{$shop->id}}">
                <input type="hidden" name="owner_id" value="{{$shop->user_id}}">
                <input type="hidden" name="email_verified_at" value="{{$user->email_verified_at}}">
                <p>Date:</p>
                <input type="date" name="date" class="date" value="{{ old('date') }}"><br>
                <p>Time:</p>
                <select name="time" class="time" value="{{ old('time') }}">
                    @for ($i = 0; $i < 13; $i++) <option value="{{$i+9}}:00">{{$i+9}}:00</option>
                        @endfor
                </select><br>
                <p>Number:</p>
                <select name="num" class="num" value="{{ old('num') }}">
                    @for ($i = 1; $i < 11; $i++) <option value="{{$i}}">{{$i}}人</option>
                        @endfor
                </select><br>

                <!-- <div class="summary">
                    <table>
                        <tr class="booking__name">
                            <td>Shop</td>
                            <td>{{$shop->name}}</td>
                        </tr>
                        <tr class="booking__date">
                            <td>Date</td>
                            <td>{{ old('time') }}</td>
                        </tr>
                        <tr class="booking__time">
                            <td>Time</td>
                            <td>{{ old('time') }}</td>
                        </tr>
                        <tr class="booking__num">
                            <td>number</td>
                            <td>{{ old('num') }}</td>
                        </tr>
                    </table>
                </div> -->

                <div class="booking__button">
                    <button>予約する</button>
                </div>
            </form>
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
        </div>

    </div>
</div>
@endsection