<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WebhookController extends Controller
{
    public function index(Request $request, Telegram $telegram){
        Log::debug(json_encode($request->all()));
        $buttons = [
            'keyboard' => [
                [
                    [
                        'text' => 'Bloklarning bir biriga mosligini tekshirish',
                        'callback_data' => '1'
                    ]
                ]
            ],
            'resize_keyboard' => true
        ];
        $data = $request->input('message')['text'];
        if ($data == '/start'){
            $telegram->sendButtons($request->input('message')['from']['id'], 'Ichki blok seriya raqamini kiriting', json_encode($buttons));
        } else {
            $telegram->sendButtons($request->input('message')['from']['id'], 'Tashqi blok seriya raqamini kiriting', json_encode($buttons));
        }
    }
}
