<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Location; // Припускаємо, що модель Location має атрибути latitude, longitude, name, description
use App\Models\LinkOnMap; // <--- ДОДАНО: Імпорт моделі для посилань
use App\Repositories\ElasticSearchRepository;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function __construct(
        protected LocationRepository $locationRepository,
        protected ElasticSearchRepository $elasticSearchRepository, // Якщо використовується для пошуку
    ) {
    }

    public function index(): Factory|View|Application
    {
        $allLocations = Location::all(); // Або $this->locationRepository->getAll();
        $initialCenter = '50.4501,30.5234';
        if ($allLocations->isNotEmpty() && $allLocations->first()->latitude && $allLocations->first()->longitude) {
            $firstLocation = $allLocations->first();
            $initialCenter = $firstLocation->latitude . ',' . $firstLocation->longitude;
        }

        // <--- ДОДАНО: Отримання даних для кнопок-фільтрів
        $mapFilterLinks = LinkOnMap::orderBy('name')->get();

        return view('map.map_search', [
            'locations' => [], // Ви передаєте порожній масив тут, це очікувано?
            'centerMapOn' => $initialCenter,
            'searchQuery' => '',
            'mapLinks' => $mapFilterLinks, // <--- ДОДАНО: Передача посилань у view
        ]);
    }

    public function search(Request $request): Factory|View|Application
    {
        $query = $request->get('query', '');
        $types = $request->has('type') ? explode(',', $request->get('type')) : [];
        $categories = $request->has('category') ? explode(',', $request->get('category')) : [];

        $foundLocations = $this->locationRepository->search($query, $types, $categories);

        $centerCoordinates = '50.4501,30.5234';

        if ($foundLocations->isNotEmpty()) {
            $firstResult = $foundLocations->first();
            if (isset($firstResult->latitude) && isset($firstResult->longitude)) {
                $centerCoordinates = $firstResult->latitude . ',' . $firstResult->longitude;
            } elseif (isset($firstResult->location) && is_string($firstResult->location) && str_contains($firstResult->location, ',')) {
                $centerCoordinates = $firstResult->location;
            }
        }

        // <--- ДОДАНО: Отримання даних для кнопок-фільтрів
        $mapFilterLinks = LinkOnMap::orderBy('name')->get();

        return view('map.map_search', [
            'locations' => $foundLocations,
            'centerMapOn' => $centerCoordinates,
            'searchQuery' => $query,
            'mapLinks' => $mapFilterLinks, // <--- ДОДАНО: Передача посилань у view
        ]);
    }
}
