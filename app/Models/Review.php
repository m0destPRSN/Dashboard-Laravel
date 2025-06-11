<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'user_id',
        // 'name', // Видалено
        'rating',
        'review_text',
    ];

    /**
     * Отримати локацію, до якої належить відгук.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Отримати користувача, який залишив відгук.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
