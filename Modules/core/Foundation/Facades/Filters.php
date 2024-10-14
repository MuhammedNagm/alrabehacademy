<?php

namespace Modules\Foundation\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Foundation\Classes\Hooks\Filters as HookFilters;

class Filters extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HookFilters::class;
    }
}
