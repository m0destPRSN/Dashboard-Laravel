<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testElasticSearchConnection(Client $client)
    {
        // Перевірка підключення
        $info = $client->info();

        dd($info);
    }
}
