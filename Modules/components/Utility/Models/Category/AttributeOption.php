<?php

namespace Modules\Components\Utility\Models\Category;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeOption extends BaseModel
{
    use PresentableTrait, LogsActivity;

    public $timestamps = false;

    protected $table = 'utility_attribute_options';


    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.attribute_option';


    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class);
    }

}
