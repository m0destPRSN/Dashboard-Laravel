<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use Searchable;
    use HasFactory;
    protected $table = 'locations';
    protected $guarded=['id'];
    public function toElasticsearchDocumentArray(): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'id_type' => $this->id_type,
            'id_category' => $this->id_category,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

}
