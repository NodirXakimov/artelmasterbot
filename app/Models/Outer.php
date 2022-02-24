<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outer extends Model
{
    use HasFactory;
    protected $fillable = ['seria'];

    public function inner()
    {
        return $this->hasMany(Inner::class);
    }
}
