<?php

namespace Modules\Components\Utility\Facades\Address;

use Illuminate\Support\Facades\Facade;

class Address extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Components\Utility\Classes\Address\Address::class;
    }
}
