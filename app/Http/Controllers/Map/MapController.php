<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Repositories\ElasticSearchRepository;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('map.map_search', compact('locations'));
    }

    public function __construct(
        protected LocationRepository $locationRepository,
        protected ElasticSearchRepository $elasticSearchRepository,
    ) {
    }

    public function search(Request $request): Factory|View|Application
    {
        $query = $request->get('query', '');
        $locations = $this->locationRepository->search($query);

        return view('map.map_search', [
            'locations' => $locations,
            'center' => $locations->first()?->location ?? '43.4142989,-124.2301242', // центр на первую найденную локацию
        ]);
    }

}
