<?php

namespace Modules\Foundation\Search\Commands;

use Illuminate\Console\Command;
use Modules\Foundation\Search\Indexer;

class Index extends Command
{

    protected $signature = 'corals-search:all {model_class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build the searchindex';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $indexer = new Indexer();
        $indexer->indexAllByClass($this->argument('model_class'));
    }
}
