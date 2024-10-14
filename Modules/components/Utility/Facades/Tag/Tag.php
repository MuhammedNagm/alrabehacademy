<?php

namespace Modules\Components\Utility\Facades\Tag;

use Illuminate\Support\Facades\Facade;

class Tag extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Components\Utility\Classes\Tag\TagManager::class;
    }
}
