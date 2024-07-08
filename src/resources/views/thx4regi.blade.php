@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection
@section('content')
<div class="content">
    <div class="heading">
        <h2>会員登録ありがとうございます</h2>
    </div>

    <div class="guide">
        <a class="guide__login " href="{{ route('login') }}">
            {{ __('ログインする') }}
        </a>
    </div>
    <div class="guide">
        <a class="guide__login " href="{{ route('admin') }}">
            {{ __('戻る') }}
        </a>
    </div>
</div>
@endsection