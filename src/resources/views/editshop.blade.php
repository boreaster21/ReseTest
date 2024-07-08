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
            <img src="{{Storage::url($shop->URL)}}" alt="{{$shop->name}}">
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
            <h2 class="ttl__txt">編集</h2>
        </div>
        <div class="input">
            <form action="/owner/edit" class="form" method="post" enctype="multipart/form-data">
                @csrf
                <table>
                    <tr>
                        <th>店名：</th>
                        <td><input type="text" name="name" class="input" value="{{$shop->name}}" placeholder="{{$shop->name}}" required></td>
                    </tr>
                    <tr>
                        <th>地域：</th>
                        <td>
                            <select name="area" class="input" value="{{ old('name') }}" required>
                                @if ( $shop->area == '東京都')
                                <option value="東京都" selected>東京都</option>
                                <option value="大阪府">大阪府</option>
                                <option value="福岡県">福岡県</option>
                                @elseif( $shop->area == '大阪府')
                                <option value="東京都">東京都</option>
                                <option value="大阪府" selected>大阪府</option>
                                <option value="福岡県">福岡県</option>
                                @elseif( $shop->area == '福岡県')
                                <option value="東京都">東京都</option>
                                <option value="大阪府">大阪府</option>
                                <option value="福岡県" selected>福岡県</option>
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>ジャンル：</th>
                        <td><select name="ganre" class="input" value="{{ old('name') }}" required>
                                @if ( $shop->ganre == '寿司')
                                <option value="寿司" selected>寿司</option>
                                <option value="焼き肉">焼き肉</option>
                                <option value="居酒屋">居酒屋</option>
                                <option value="イタリアン">イタリアン</option>
                                <option value="ラーメン">ラーメン</option>
                                @elseif( $shop->ganre == '焼き肉')
                                <option value="寿司">寿司</option>
                                <option value="焼き肉" selected>焼き肉</option>
                                <option value="居酒屋">居酒屋</option>
                                <option value="イタリアン">イタリアン</option>
                                <option value="ラーメン">ラーメン</option>
                                @elseif( $shop->ganre == '居酒屋')
                                <option value="寿司">寿司</option>
                                <option value="焼き肉">焼き肉</option>
                                <option value="居酒屋" selected>居酒屋</option>
                                <option value="イタリアン" selected>イタリアン</option>
                                <option value="ラーメン">ラーメン</option>
                                @elseif( $shop->ganre == 'イタリアン')
                                <option value="寿司">寿司</option>
                                <option value="焼き肉">焼き肉</option>
                                <option value="居酒屋">居酒屋</option>
                                <option value="イタリアン" selected>イタリアン</option>
                                <option value="ラーメン">ラーメン</option>
                                @elseif( $shop->ganre == 'ラーメン')
                                <option value="寿司">寿司</option>
                                <option value="焼き肉">焼き肉</option>
                                <option value="居酒屋">居酒屋</option>
                                <option value="イタリアン" selected>イタリアン</option>
                                <option value="ラーメン" selected>ラーメン</option>
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>詳細説明：</th>
                        <td><textarea name="detail" cols="30" rows="3" value="{{ old('detail') }}" required>{{$shop->detail}}</textarea></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="shop_id" class="input" value="{{$shop->id}}"></td>
                    </tr>
                    <tr>
                        <th>イメージ：</th>
                        <td><input type="file" name="image" class="input" value="{{Storage::url($shop->URL)}}"></td>
                        <td><input type="hidden" name="shop_id" class="input" value="{{$shop->id}}"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><button type="submit">保存</button></td>
                    </tr>
                </table>
            </form>
        </div>
        @if (session('message'))
        <div class="todo__alert--success">{{ session('message') }}</div>
        @endif
        @if ($errors->any())
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
@endsection