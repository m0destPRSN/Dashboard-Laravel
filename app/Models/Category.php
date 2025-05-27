<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable=['category','id_type', 'photo_path'];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(Type::class, 'id_type');
    }
}
