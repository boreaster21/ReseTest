<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailSendController extends Controller
{
    public function send(Request $req)
    {
        // Mail::send(new SendMail());

        // $data = [$req->message, $req->shop_name];

        // Mail::send('emails.message', $data, function ($message) {
        //     $message->to('abc@example.com', 'Test')
        //     ->from('Rese@Rese.co.jp','Rese')
        //     ->subject('予約したお店からご連絡');
        // });

        $rules = [
            'name' => 'required',
            'message' => 'required'
        ];

        $messages = [
            'name.required' => '名前を入力してください。',
            'message.required' => 'メッセージを入力してください。'
        ];

        $validator = Validator::make($req->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validate();

        Mail::to('admin@hoge.co.jp')->send(new SendMail($data));

        session()->flash('success', '送信しました！');
        return back();
    }
    public function remind(Request $req)
    {
        //
    }
}
