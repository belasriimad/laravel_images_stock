<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id', 'user_id', 'qty', 'total'
    ];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
