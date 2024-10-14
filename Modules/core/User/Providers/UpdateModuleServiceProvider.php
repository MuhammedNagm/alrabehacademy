<?php

namespace Modules\User\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-user';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
