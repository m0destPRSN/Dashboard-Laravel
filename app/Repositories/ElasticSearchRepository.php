<?php

namespace App\Repositories;
use App\Interfaces\IGetElasticSearchInformation;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ElasticSearchRepository
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(IGetElasticSearchInformation $model, string $query = '')
    {
        $items = $this->searchOnElasticsearch($model,$query);

        return $this->buildCollection($items, get_class($model));
    }

    private function searchOnElasticsearch(IGetElasticSearchInformation $model,string $query = ''): array
    {
        $items = $this->elasticsearch->search([
            'index' => $model->getElasticSearchIndex(),
            'type' => $model->getElasticSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => $model->getElasticSearchableFields(),
                        'query' => $query,
                    ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items,string $modelClassName)
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return $modelClassName::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }
}
