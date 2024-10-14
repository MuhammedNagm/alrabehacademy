<?php

namespace Modules\Foundation\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class ModulesForm extends IlluminateFacade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Foundation\Classes\ModulesForm::class;
    }
}
