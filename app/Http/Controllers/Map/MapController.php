<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Location; // Припускаємо, що модель Location має атрибути latitude, longitude, name, description
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
        // Можливо, тут ви захочете показати всі локації або якусь дефолтну карту
        $allLocations = Location::all(); // Або $this->locationRepository->getAll();
        $initialCenter = '50.4501,30.5234'; // Київ за замовчуванням, якщо немає локацій
        if ($allLocations->isNotEmpty() && $allLocations->first()->latitude && $allLocations->first()->longitude) {
            $firstLocation = $allLocations->first();
            $initialCenter = $firstLocation->latitude . ',' . $firstLocation->longitude;
        }

        return view('map.map_search', [
            'locations' => [],
            'centerMapOn' => $initialCenter,
            'searchQuery' => '' // Для відображення поля пошуку
        ]);
    }

    public function search(Request $request): Factory|View|Application
    {
        $query = $request->get('query', '');
        // Припускаємо, що search повертає колекцію об'єктів Location
        // або порожню колекцію, якщо нічого не знайдено.
        $foundLocations = $this->locationRepository->search($query);

        $centerCoordinates = '50.4501,30.5234'; // Київ за замовчуванням

        if ($foundLocations->isNotEmpty()) {
            $firstResult = $foundLocations->first();
            // Переконайтеся, що у вашого об'єкта Location є властивості latitude та longitude
            if (isset($firstResult->latitude) && isset($firstResult->longitude)) {
                $centerCoordinates = $firstResult->latitude . ',' . $firstResult->longitude;
            } elseif (isset($firstResult->location) && is_string($firstResult->location) && str_contains($firstResult->location, ',')) {
                // Якщо у вас рядок 'lat,lng'
                $centerCoordinates = $firstResult->location;
            }
        }

        return view('map.map_search', [
            'locations' => $foundLocations,
            'centerMapOn' => $centerCoordinates,
            'searchQuery' => $query // Щоб відобразити поточний запит у формі
        ]);
    }
}
