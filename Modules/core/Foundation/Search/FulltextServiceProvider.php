<?php

namespace Modules\Foundation\Search;

use Illuminate\Support\ServiceProvider;
use Modules\Foundation\Search\Commands\Index;
use Modules\Foundation\Search\Commands\IndexOne;
use Modules\Foundation\Search\Commands\Install;
use Modules\Foundation\Search\Commands\UnindexOne;

class FulltextServiceProvider extends ServiceProvider
{

    protected $commands = [
        Index::class,
        IndexOne::class,
        UnindexOne::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        $this->app->bind(
            SearchInterface::class,
            Search::class
        );
    }
}
