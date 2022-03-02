<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'username', 'state', 'chat_id', 'last_sent_message', 'language_code', 'is_bot'];
}
