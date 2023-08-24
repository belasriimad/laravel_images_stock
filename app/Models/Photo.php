<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'url', 'body', 'is_free', 'price', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
