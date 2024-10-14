<?php

namespace Modules\Media\Providers;

use Modules\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-media';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
