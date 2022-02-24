<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Models\Chat;
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
        $chat_id = $request->input('message')['chat']['id'];
        $chat = Chat::where('chat_id', '=', $chat_id)->first();
        if ($chat === null)
        {
            $chat = new Chat(['chat_id' => $chat_id]);
            $telegram->sendButtons($chat->chat_id, '', json_encode($buttons));
        }
        Log::debug(json_encode($chat_id));
//        if ($data == '/start'){
//            $telegram->sendButtons($request->input('message')['from']['id'], '', json_encode($buttons));
//        } else {
//            $telegram->sendButtons($request->input('message')['from']['id'], 'Tashqi blok seriya raqamini kiriting', json_encode($buttons));
//        }
    }
}
