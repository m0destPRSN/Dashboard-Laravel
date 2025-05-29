<?php

namespace App\Repositories;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class LocationRepository
{
    /**
     * Search locations based on query, types, and categories.
     *
     * @param string $query
     * @param array $types
     * @param array $categories
     * @return Collection
     */
    public function search(string $query = "", array $types = [], array $categories = []): Collection
    {
        $dbQuery = Location::query();

        if (!empty($query)) {
            $dbQuery->where(function (Builder $q) use ($query) {
                $q->where('location', 'LIKE', "%{$query}%")
                    ->orWhere('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        if (!empty($types)) {
            // Assuming 'type_id' is the foreign key in your 'locations' table
            // and $types contains an array of type IDs.
            $dbQuery->whereIn('id_type', $types);
        }

        if (!empty($categories)) {
            // Assuming 'category_id' is the foreign key in your 'locations' table
            // and $categories contains an array of category IDs.
            $dbQuery->whereIn('id_category', $categories);
        }

        return $dbQuery->get();
    }
}
