<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Telegram $telegram) {

    $buttons = [
        'inline_keyboard' => [
            [
                [
                    'text' => 'button1',
                    'callback_data' => '1'
                ],
                [
                    'text' => 'button2',
                    'callback_data' => '2'
                ],
            ],
            [
                [
                    'text' => 'button4',
                    'callback_data' => '3'
                ],
            ]
        ]
    ];

    $sendMessage = $telegram->sendButtons(685039285, 'Logo of the company!', json_encode($buttons));
    $sendMessage = json_decode($sendMessage);
    dd($sendMessage);

//    $sendMessage = $telegram->sendMessage(685039285, 'Logo of the company!');
//    $sendMessage = json_decode($sendMessage);
//    $telegram->sendDocument(685039285, 'artel.jpg', $sendMessage->result->message_id);

});

Route::get('/download', function (){
   return response()->download(public_path('artel.jpg'));
});
