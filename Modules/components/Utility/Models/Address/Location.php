<?php

namespace Modules\Components\Utility\Models\Address;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Cviebrock\EloquentSluggable\Sluggable;


class Location extends BaseModel
{
    use PresentableTrait, LogsActivity, Sluggable;

    protected $table = 'utility_locations';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.location';

    protected static $logAttributes = ['name'];

    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
