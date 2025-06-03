<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailSendController extends Controller
{
    public function mail(){

        $data = [];

        // メールの中身はemails.test(emailsディレクトリのtestブレードファイルを示す)に記述
        Mail::send('emails.test', $data, function($message){
            // 送信した宛先のメール
            $message->to('abc987@example.com', 'Test')
            // メールのタイトル
            ->subject('This is a test mail');
        });
    }
}
