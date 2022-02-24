<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
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

    $sendMessage = $telegram->sendButtons(685039285, 'Logo of the company!', json_encode($buttons));

});

Route::get('/download', function (){
   return response()->download(public_path('artel.jpg'));
});

Route::get('/artisan', function(){
   phpinfo();
});

Route::post('/webhook', [App\Http\Controllers\WebhookController::class, 'index']);
