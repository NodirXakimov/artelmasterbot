<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WebhookController extends Controller
{
    public function index(Request $request){
        Log::debug(json_encode($request->all()));
    }
}
