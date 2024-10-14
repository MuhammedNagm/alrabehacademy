<?php

namespace Modules\Settings\Http\Controllers;

ini_set('max_execution_time', 300);

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Settings\Http\Requests\ModuleRequest;
use Modules\Settings\Models\Module;
use Illuminate\Http\Request;

class ModulesController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('settings.models.module.resource_url');
        $this->title = 'Settings::module.module.title';
        $this->title_singular = 'Settings::module.module.title_singular';

        parent::__construct();
    }

    /**
     * @param ModuleRequest $request
     * @return mixed
     */
    public function index(ModuleRequest $request)
    {

    }


}
