<?php

namespace Modules\Components\Utility\Facades;

use Illuminate\Support\Facades\Facade;

class Utility extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Components\Utility\Classes\Utility::class;
    }
}
