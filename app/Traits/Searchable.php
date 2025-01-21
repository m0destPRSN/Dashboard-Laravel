<?php

namespace App\Traits;

use App\Observers\ElasticSearch\ElasticSearchObserver;

trait Searchable
{
    public function bootSearchable()
    {
        if(config('services.search.enabled'))
        //реєструємо для кожної моделі обсерв
            static::observe(ElasticSearchObserver::class);
    }
    public function elasticSearchIndex()
    {

    }
    public function elasticSearchDelete()
    {

    }
}
