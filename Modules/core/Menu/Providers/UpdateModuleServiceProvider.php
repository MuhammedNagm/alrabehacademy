<?php

namespace Modules\Menu\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-menu';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
