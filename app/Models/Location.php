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
    // Using $guarded = ['id'] is fine. It means all other attributes are mass assignable.
    // Alternatively, you could use $fillable with an array of attributes.
    protected $guarded = ['id'];

    protected $casts = [
        'photo_paths' => 'array',
        // 'location' => 'array', // Uncomment if you store location as JSON
    ];

    /**
     * Get the reviews for the location.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); // Orders reviews by newest first
    }

    /**
     * Get the user who created the location.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the type of the location.
     */
    public function type()
    {
        // Assuming your foreign key in the 'locations' table is 'type_id'
        // If it's 'id_type' as hinted in your toElasticsearchDocumentArray, use that:
        // return $this->belongsTo(Type::class, 'id_type');
        return $this->belongsTo(Type::class);
    }

    /**
     * Get the category of the location.
     */
    public function category()
    {
        // Assuming your foreign key in the 'locations' table is 'category_id'
        // If it's 'id_category' as hinted in your toElasticsearchDocumentArray, use that:
        // return $this->belongsTo(Category::class, 'id_category');
        return $this->belongsTo(Category::class);
    }

    // Your existing Elasticsearch methods
    public function toElasticsearchDocumentArray(): array
    {
        // Consider using the relationships here if they are defined correctly
        // e.g., $this->type->type instead of Type::find($this->id_type)->type
        // This would require 'id_type' to be the correct foreign key name in the type() relationship
        return [
            'id' => $this->id,
            'location' => $this->location,
            'name_type' => $this->type_id ? Type::find($this->type_id)->type : null, // or $this->type->type if relationship is set up
            'name_category' => $this->category_id ? Category::find($this->category_id)->category : null, // or $this->category->category
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function getElasticSearchIndex()
    {
        return $this->table;
    }

    public function getElasticSearchType(){
        return '_doc';
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
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }


}
