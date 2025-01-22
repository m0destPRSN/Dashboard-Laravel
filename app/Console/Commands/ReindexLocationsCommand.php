<?php

namespace App\Console\Commands;

use App\Models\Location;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex-locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all locations to Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Indexing all locations. This might take a while...');

        foreach (Location::cursor() as $location) {
            $this->elasticsearch->index([
                'index' => $location->getElasticSearchIndex(),
                'type' => $location->getElasticSearchType(),
                'id' => $location->getKey(),
                'body' => $location->toElasticsearchDocumentArray(),
            ]);

            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}
