<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)      //引数を追加
    {
        $this->data = $data;      //追加
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.message')
        // ->to('XXXXX@XXXXX.jp', '鈴木太郎')
        // ->from('XXX@XXXX', '佐藤一郎')
        // ->subject('テストメールです。');

        return $this->view('emails.message')
            ->subject('予約店舗よりメッセージ')
            ->from('Rese@sample.com', 'Rese事務局')
            ->subject('予約した店舗よりメッセージです')
            ->with('data', $this->data);
    }
}
