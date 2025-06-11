<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkOnMap extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'link',
        'photo_path',
        // add other fillable fields if needed
    ];
}
