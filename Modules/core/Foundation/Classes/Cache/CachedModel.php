<?php

namespace Modules\Foundation\Classes\Cache;

use Modules\Foundation\Traits\Cache\Cachable;
use Illuminate\Database\Eloquent\Model;

abstract class CachedModel extends Model
{
    use Cachable;
}
