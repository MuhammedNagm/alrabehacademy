<?php

namespace Modules\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Modules extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Settings\Classes\Modules::class;
    }
}
