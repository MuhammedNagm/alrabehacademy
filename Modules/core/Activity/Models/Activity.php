<?php

namespace Modules\Activity\Models;

use Modules\Foundation\Traits\Hookable;
use Modules\Foundation\Traits\HashTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HashTrait, Hookable;

    const MAX_PROPERTIES_LENGTH = 30000; // Set your desired limit

    // Mutator for properties
    public function setPropertiesAttribute($value)
    {
        if (is_string($value) && strlen($value) > self::MAX_PROPERTIES_LENGTH) {
            $this->attributes['properties'] = null; // Set to null if it exceeds the limit
        } else {
            $this->attributes['properties'] = $value; // Otherwise, set the value
        }
    }
}
