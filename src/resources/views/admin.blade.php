@extends('layouts.app')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/allshop.css') }}">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="{{ asset('js/favs.js') }}"></script>

@endsection

@section('nav')
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
    <div>
        <h2>店舗代表者作成ページ</h2>
    </div>
    <div class="form">
        <form action="/admin" method="post">
            @csrf
            <p>ユーザー</p>
            <select name="id">
                @foreach ($users as $user)
                <option value="{{$user->id}}">ID:{{$user->id}}, ユーザー名:{{$user->name}}, 権限:{{$user->role}}</option>
                @endforeach
            </select>
            <p>権限選択</p>
            <select name="role">
                <option value="100">一般ユーザー:000</option>
                <option value="100">店舗代表者:100</option>
                <option value="999">管理者・開発者:999</option>
            </select>
            <button>権限付与</button>
        </form>
    </div>

    <!-- <div class="form">
    <form action="/admin" method="post">
        @csrf
        <p>店舗代表者名</p>
        <input type="text" name="name" value="{{old('name')}}" placeholder="店舗代表者名" required>
        <p>メールアドレス</p>
        <input type="email" name="email" value="{{old('email')}}" placeholder="メールアドレス" required>
        <p>パスワード</p>
        <input type="password" name="password" value="{{old('passward')}}" placeholder="メールアドレス" required>
        <p>パスワード</p>
        <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}" placeholder="メールアドレス確認" required>
        <button>店舗代表者作成</button>
</div> -->
</div>
@endsection