<?php

namespace Modules\User\Models;

use Modules\Foundation\Traits\Hookable;
use Modules\Foundation\Traits\HashTrait;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HashTrait, Hookable;
}
