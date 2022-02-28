<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Models\Chat;
use App\Models\Inner;
use App\Models\Outer;
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
                        'text' => '✅ Bloklarning bir biriga mosligini tekshirish',
                        'callback_data' => '1'
                    ]
                ]
            ],
            'resize_keyboard' => true
        ];
        $goToMainKeyboard = [
            'keyboard' => [
                [
                    [
                        'text' => '🔙 Bosh menyuga qaytish'
                    ]
                ]
            ],
            'resize_keyboard' => true
        ];
        $chat_id = (integer)$request->input('message')['chat']['id'];
        $chat = Chat::firstOrCreate(
            ['chat_id' => $chat_id],
            ['state' => 0]
        );
        if($request->input('message')['text'] == "🔙 Bosh menyuga qaytish") {
            $chat->state = 1;
            $chat->save();
            $telegram->sendButtons($chat->chat_id, "📎", json_encode($buttons));
            return;
        }
        switch ($chat->state)
        {
            case "0":   // Default
                $telegram->sendButtons($chat->chat_id, "🎉 Artel Master botga xush kelibsiz !!!", json_encode($buttons));
                $chat->state = 1;
                $chat->save();
                break;
            case "1":
                if($request->input('message')['text'] == "✅ Bloklarning bir biriga mosligini tekshirish") {
                    $telegram->sendButtons($chat->chat_id, 'Ichki blok seriya raqamining ilk 8 ta belgisini kiriting:', json_encode($goToMainKeyboard));
                    $chat->state = 2;
                    $chat->save();
                } else {
                    $telegram->sendButtons($chat->chat_id, "🧾 Ma'lumot olish uchun quyidagi tugmani bosing", json_encode($buttons));
                }
                break;
            case "2":   // Requested
                if (Inner::where('seria', '=', $request->input('message')['text'])->exists()) {
                    $chat->last_sent_message = $request->input('message')['text'];
                    $chat->state = '3';
                    $chat->save();
                    $telegram->sendButtons($chat->chat_id, 'Tashqi blok seriya raqamining ilk 8 ta belgisini kiriting:', json_encode($goToMainKeyboard));
                } else {
                    $telegram->sendButtons($chat->chat_id, "⚠ Iltimos ichki blok seriya raqamining ilk 8 ta belgisini <b>to'g'ri</b> kiriting:", json_encode($goToMainKeyboard));
                }
                break;
            case "3":   // Asked
                if (Outer::where('seria', '=', $request->input('message')['text'])->exists()) {
                    $outer = Outer::where('seria', '=', $request->input('message')['text'])->first();
                    $last_sent_message = $chat->last_sent_message;
                    $isBlocksMatch = $outer->inners->contains(function ($key) use ($last_sent_message){
                        return $key->seria == $last_sent_message;
                    });
                    if ($isBlocksMatch) {
                        $telegram->sendButtons($chat->chat_id, "✅ Bu ichki va tashqi bloklar bir biriga mos keladi.", json_encode($buttons));
                    } else {
                        $telegram->sendButtons($chat->chat_id, "⛔️Bu ichki va tashqi bloklar bir biriga mos kelmaydi.", json_encode($buttons));
                    }
                } else {
                    $telegram->sendButtons($chat->chat_id, "⚠ Iltimos tashqi blok seriya raqamining ilk 8 ta belgisini <b>to'g'ri</b> kiriting:", json_encode($goToMainKeyboard));
                }
                break;
            default:
                $telegram->sendMessage($chat->chat_id, 'default case working');

        }
    }
}
