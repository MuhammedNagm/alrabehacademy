<?php

namespace Modules\Foundation\Models;

use Modules\Foundation\Traits\HashTrait;
use Modules\Foundation\Traits\Hookable;
use Modules\Foundation\Traits\Language\Translatable;
use Modules\Foundation\Traits\ModelHelpersTrait;
use Modules\Settings\Traits\CustomFieldsModelTrait;
use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class BaseModel extends Model
{
    use HashTrait, AuditableTrait, Hookable, CustomFieldsModelTrait, ModelHelpersTrait, Translatable;

    protected static function boot()
    {
        parent::boot();
    }

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->initialize();

        return parent::__construct($attributes);
    }

    /**
     * init model
     */
    public function initialize()
    {
        $config = config($this->config);

        if ($config) {
            if (isset($config['presenter'])) {
                $this->setPresenter(new $config['presenter']);
                unset($config['presenter']);
            }

            foreach ($config as $key => $val) {
                if (property_exists(get_called_class(), $key)) {
                    $this->$key = $val;
                }
            }
        }
    }
}
