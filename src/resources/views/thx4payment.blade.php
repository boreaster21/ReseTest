@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection
@section('content')
<div class="content">
    <div class="heading">
        <h2>お支払いありがとうございます</h2>
    </div>
    <div class="form">
        <a href="/" class="back">戻る</a>
    </div>
</div>
@endsection