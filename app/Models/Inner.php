<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inner extends Model
{
    use HasFactory;
    protected $fillable = ['seria', 'outer_id'];
    public function outer()
    {
        return $this->belongsTo(Outer::class);
    }
}
