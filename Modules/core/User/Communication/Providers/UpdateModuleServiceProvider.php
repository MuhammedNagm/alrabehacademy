<?php

namespace Modules\User\Communication\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-notification';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
