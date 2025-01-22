<?php

namespace App\Console\Commands;

use App\Models\Location;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class DeleteLocationsFromElasticsearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:delete-locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all locations from Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Deleting all locations from Elasticsearch. This might take a while...');

        foreach (Location::cursor() as $location) {
            $this->elasticsearch->delete([
                'index' => $location->getElasticSearchIndex(),
                'type' => $location->getElasticSearchType(),
                'id' => $location->getKey(),
            ]);

            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}
