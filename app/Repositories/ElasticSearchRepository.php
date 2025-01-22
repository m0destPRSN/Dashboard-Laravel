<?php

namespace App\Repositories;
use App\Interfaces\IGetElasticSearchInformation;
use App\Interfaces\LocationRepository;
use App\Models\Location;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class ElasticSearchRepository implements LocationRepository
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search( string $query = '')
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new Location();

        try {
            $response = $this->elasticsearch->search([
                'index' => $model->getElasticSearchIndex(),
                'type' => $model->getElasticSearchType(),
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'multi_match' => [
                                        'query' => $query,
                                        'fields' => $model->getElasticSearchableFields(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            return $response->asArray();
        } catch (\Elastic\Elasticsearch\Exception\ClientResponseException $e) {
            \Log::error('Elasticsearch error: ' . $e->getMessage());
            return [];
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage());
            return [];
        }
    }



    private function buildCollection(array $items)
    {
        if (empty($items['hits']['hits'])) {
            return collect();
        }

        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Location::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }

}
