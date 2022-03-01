<?php

use App\Http\Controllers\AdminController;
use App\Models\Outer;
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
Route::post('/', [App\Http\Controllers\WebhookController::class, 'index']);

Route::post('/webhook', [App\Http\Controllers\WebhookController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function (){
    Route::get('/', [AdminController::class, 'index']);
});

Route::redirect('/', '/home');
