<?php

namespace Modules\Activity\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-activity';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
