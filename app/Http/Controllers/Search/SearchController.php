<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Repositories\ElasticSearchRepository;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected LocationRepository $locationRepository,
        protected ElasticSearchRepository $elasticSearchRepository,
    ) {
    }

    /**
     * Пошук локацій через Elasticsearch
     *
     */
    public function search(Request $request): Factory|View|Application
    {
        $query = $request->get('query', '');

        $types = $request->has('type') ? explode(',', $request->get('type')) : [];
        $categories = $request->has('category') ? explode(',', $request->get('category')) : [];

        $locations = $this->locationRepository->search($query, $types, $categories);

        return view('home.home_search', compact('locations'));
    }

}
