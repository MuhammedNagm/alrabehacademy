<?php

namespace Modules\Foundation\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Language extends IlluminateFacade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return new \Modules\Foundation\Classes\Language();
    }
}
