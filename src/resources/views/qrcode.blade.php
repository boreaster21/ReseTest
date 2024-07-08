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
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2>Simple QR Code</h2>
            </div>
            <div class="card-body">
                <img id="qrcode" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($url ?? '')) !!}">
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <button id="download-btn">Download QR code</button>
            </div>
        </div>

    </div>
    <script>
        var downloadBtn = document.getElementById('download-btn'); //htmlのdownload-btnの要素を格納
        var qrcodeImg = document.getElementById('qrcode'); //htmlのqrcode

        //下記一行はdownload_btnをクリックしたときの動作を記載しますよという意味
        downloadBtn.addEventListener('click', function() {
            var downloadLink = document.createElement('a'); //ハイパーリンクを作成宣言(aタグ)
            downloadLink.href = qrcodeImg.src; //qrcode->qrcodeImgのsrcの部分のリンクを格納してる
            downloadLink.download = 'qrcode.png'; //download時のファイル名を記載

            // ダウンロードの確認ダイアログを表示する
            if (confirm('二次元コードをダウンロードしていいですか?')) {
                downloadLink.click(); //実行するという意味のコード
            }
        });
    </script>
</div>
@endsection