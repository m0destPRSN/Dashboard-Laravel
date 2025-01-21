<?php

namespace App\Observers\ElasticSearch;

use Elastic\Elasticsearch\Client;

class ElasticSearchObserver
{
    private $elasticsearchClient;
    public function __construct( Client $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function saved($model) // мейбі created
    {
        $model->elasticSearchIndex($this->elasticsearchClient);
    }

    public function deleted($model)
    {
        $model->elasticSearchDelete($this->elasticsearchClient);
    }
}
