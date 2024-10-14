<?php

namespace Modules\Theme\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-theme';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
