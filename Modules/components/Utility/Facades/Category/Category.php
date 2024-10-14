<?php

namespace Modules\Components\Utility\Facades\Category;

use Illuminate\Support\Facades\Facade;

class Category extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Components\Utility\Classes\Category\CategoryManager::class;
    }
}
