<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['type', 'location_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Relationship is participants, not participations
    public function participants()
    {
        return $this->hasMany(Participation::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
