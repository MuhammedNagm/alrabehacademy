<?php

namespace Modules\User\Models;

use Modules\Foundation\Traits\Hookable;
use Modules\Foundation\Traits\HashTrait;
use Modules\Foundation\Traits\Language\Translatable;
use Modules\Foundation\Transformers\PresentableTrait;
use Modules\Settings\Traits\CustomFieldsModelTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;
use Yajra\Auditable\AuditableTrait;

class Role extends SpatieRole
{
    use PresentableTrait, LogsActivity, HashTrait, AuditableTrait, Hookable, CustomFieldsModelTrait, Translatable;
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'user.models.role';

    protected static $logAttributes = ['name'];
}
