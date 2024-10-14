<?php

namespace Modules\Settings\Models;

use Modules\Foundation\Models\BaseModel;

class Country extends BaseModel
{
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.models.country';


    protected $guarded = ['id'];


}
