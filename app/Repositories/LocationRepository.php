<?php

namespace App\Repositories;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class LocationRepository
{
    public function search($query = ""): Collection
    {
        return Location::query()
            ->where('location', 'LIKE', "%$query%")
            ->orWhere('title', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->get();
    }
}
