<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Telegram
{
    protected $http;
    protected $bot;
    const URL = "https://api.telegram.org/bot";

    public function __construct(Http $http, $bot)
    {
        $this->http = $http;
        $this->bot = $bot;
    }

    public function sendMessage($chat_id, $message): \Illuminate\Http\Client\Response
    {
        return $this->http::post(self::URL.$this->bot.'/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html'
        ]);
    }

    public function sendDocument($chat_id, $file, $reply_id = null)
    {
        return $this->http::attach('document', Storage::get('public/'.$file), 'document.jpg')
        ->post(self::URL.$this->bot.'/sendDocument', [
            'chat_id' => $chat_id,
            'reply_to_message_id' => $reply_id
        ]);
    }

    public function sendButtons($chat_id, $message, $button): \Illuminate\Http\Client\Response
    {
        return $this->http::post(self::URL.$this->bot.'/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => $button
        ]);
    }

    public function sendPhoto($chat_id, $photo, $caption = null ,$reply_id = null)
    {
        return $this->http::attach('photo', Storage::get('public/'.$photo), 'photo.jpg')
        ->post(self::URL.$this->bot.'/sendPhoto', [
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
            'caption' => $caption,
            'reply_to_message_id' => $reply_id
        ]);
    }
}
