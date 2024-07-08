<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MailSendController;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約日当日のリマインダー';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        logger('batch starts');
        if(予約当日だったら){
            logger('batch processing');
            // お客さんにリマインダーを送信する;
            $data
            
            Mail::to('admin@hoge.co.jp')->send(new SendMail($data));
        }else{
            logger('batch finished');
        }
    }
}
