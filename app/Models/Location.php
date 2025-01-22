<?php

namespace App\Models;

use App\Interfaces\IGetElasticSearchInformation;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model implements IGetElasticSearchInformation
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
            'name_type' => Type::find($this->id_type)->type,
            'name_category' => Category::find( $this->id_category)->category,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
    public function getElasticSearchIndex()
    {
        return $this->table;
    }
    public function getElasticSearchType(){
        return '_doc'; //можна потім будк якось уніфікувати
    }
    public function getElasticSearchableFields()
    {
        return  [
            'location',
            'name_type',
            'name_category',
            'title^5',
            'description',
        ];
    }
}

