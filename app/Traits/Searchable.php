<?php

namespace App\Traits;


use Elastic\Elasticsearch\Client;
use App\Observers\ElasticSearch\ElasticSearchObserver;

trait Searchable
{
    public function bootSearchable()
    {
        if(config('services.search.enabled'))
            //реєструємо для кожної моделі обсерв
            static::observe(ElasticSearchObserver::class);
    }

    public function elasticsearchIndex(Client $elasticsearchClient)
    {
        $elasticsearchClient->index([
            'index' => $this->getTable(),
            'type' => '_doc',
            'id' => $this->getKey(),
            'body' => $this->toElasticsearchDocumentArray(),
        ]);
    }

    public function elasticsearchDelete(Client $elasticsearchClient)
    {
        $elasticsearchClient->delete([
            'index' => $this->getTable(),
            'type' => '_doc',
            'id' => $this->getKey(),
        ]);
    }

    abstract public function toElasticsearchDocumentArray(): array;
}
