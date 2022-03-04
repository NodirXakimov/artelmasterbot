<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Helpers\Telegram;
use App\Models\Outer;
use App\Models\Inner;
use App\Models\Chat;


class WebhookController extends Controller
{
    public function index(Request $request, Telegram $telegram){
        Log::debug(json_encode($request->all()));
        $buttons = [
            'keyboard' => [
                [
                    [
                        'text' => '✅ Bloklarning bir biriga mosligini tekshirish',
                    ]
                ],
                [
                    [
                        'text' => "📑 Yo'riqnoma",
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
            [
                'state' => 0,
                'first_name'    => $request->input('message')['from']['first_name'],
                'last_name'     => isset($request->input('message')['from']['last_name']) ? $request->input('message')['from']['last_name'] : null,
                'username'      => isset($request->input('message')['from']['username']) ? $request->input('message')['from']['username'] : null,
                'language_code' => $request->input('message')['from']['language_code'],
                'is_bot'        => $request->input('message')['from']['is_bot'],
            ]
        );
        if (!isset($request->input('message')['text'])){
            $chat->state = 0;
        } else {
            switch ($request->input('message')['text'])
            {
                case $goToMainKeyboard['keyboard'][0][0]['text']:
                    $chat->state = 1;
                    $chat->save();
                    $telegram->sendButtons($chat->chat_id, "📎", json_encode($buttons));
                    return;
                case $buttons['keyboard'][1][0]['text']:
                    $faq = "
✅ <b>Bloklarning bir biriga mosligini tekshirish</b>
menusini tanlang! Bot sizga -
<b>Ichki blok seriya raqamining ilk 8 ta belgisini kiriting:</b>
deb javob qaytaradi!";
                    $telegram->sendMessage($chat->chat_id, $faq);
                    $telegram->sendPhoto($chat->chat_id, 'example_photo_inner.jpg', 'Ichki qism seriya raqamidan , ramka bilan ajratilgan birinchi 8 ta belgini kiriting (111ABCDE)');
                    $faq = "
Bot sizga -
<b>Tashqi blok seriya raqamining ilk 8 ta belgisini kiriting:</b>
deb javob qaytaradi !";
                    $telegram->sendMessage($chat->chat_id, $faq);
                    $telegram->sendPhoto($chat->chat_id, 'example_photo_outer.jpg', 'Tashqi qism seriya raqamidan , ramka bilan ajratilgan birinchi 8 ta belgini kiriting (113ABCDE)');
                    $faq = "
Agar siz kiritgan seriya raqamidagi bloklar bir biriga mos kelsa bot sizga -
✅ <b>Bu ichki va tashqi bloklar bir biriga mos keladi.</b>
deb javob qaytaradi!
Agar siz kiritgan seriya raqamidagi bloklar bir biriga mos kelmasa bot sizga -
⛔️<b>Bu ichki va tashqi bloklar bir biriga mos kelmaydi.</b>
deb javob qaytaradi!
Ma'lumotni to'gri kiriting! (shrift kattaligi ahamiyatsiz)
Yoki botdan :
⚠️ <b>Iltimos tashqi blok seriya raqamining ilk 8 ta belgisini to'g'ri kiriting:</b>
degan javob olasiz😉";
                    $telegram->sendMessage($chat->chat_id, $faq);
                    return;
            }
        }

        switch ($chat->state)
        {
            case "0":   // Default
                $telegram->sendButtons($chat->chat_id, "🎉 MIX Helper botga xush kelibsiz !!!", json_encode($buttons));
                $chat->state = 1;
                $chat->save();
                break;
            case "1":   // Requested with outer
                if($request->input('message')['text'] == "✅ Bloklarning bir biriga mosligini tekshirish") {
                    $telegram->sendButtons($chat->chat_id, "<b>Ichki</b> blok seriya raqamining ilk 8 ta belgisini kiriting:", json_encode($goToMainKeyboard));
                    $chat->state = 2;
                    $chat->save();
                } else {
                    $telegram->sendButtons($chat->chat_id, "🧾 Ma'lumot olish uchun pastdagi <b>Yo'riqnoma</b> tugmasini bosing!", json_encode($buttons));
                }
                break;
            case "2":   // Requested with inner
                if (Inner::where('seria', '=', $request->input('message')['text'])->exists()) {
                    $chat->last_sent_message = $request->input('message')['text'];
                    $chat->state = '3';
                    $chat->save();
                    $telegram->sendButtons($chat->chat_id, '<b>Tashqi</b> blok seriya raqamining ilk 8 ta belgisini kiriting:', json_encode($goToMainKeyboard));
                } else {
                    $telegram->sendButtons($chat->chat_id, "⚠ Iltimos ichki blok seriya raqamining ilk 8 ta belgisini <b>to'g'ri</b> kiriting:", json_encode($goToMainKeyboard));
                }
                break;
            case "3":   // Answering
                if (Outer::where('seria', '=', $request->input('message')['text'])->exists()) {
                    $outer = Outer::where('seria', '=', $request->input('message')['text'])->first();
                    $last_sent_message = $chat->last_sent_message;
                    $isBlocksMatch = $outer->inners->contains(function ($key) use ($last_sent_message){
                        return !strcasecmp($key->seria, $last_sent_message);
                    });
                    if ($isBlocksMatch) {
                        $telegram->sendButtons($chat->chat_id, "✅ Bu ichki va tashqi bloklar bir biriga mos keladi.", json_encode($buttons));
                    } else {
                        $telegram->sendButtons($chat->chat_id, "⛔️Bu ichki va tashqi bloklar bir biriga mos kelmaydi.", json_encode($buttons));
                    }
                    $chat->state = 1;
                    $chat->save();
                } else {
                    $telegram->sendButtons($chat->chat_id, "⚠ Iltimos tashqi blok seriya raqamining ilk 8 ta belgisini <b>to'g'ri</b> kiriting:", json_encode($goToMainKeyboard));
                }
                break;
            default:
                $telegram->sendMessage($chat->chat_id, 'default case working');

        }
    }
}
