<?php

namespace Modules\Components\Utility\Models\Rating;

use Modules\Foundation\Models\BaseModel;
use Modules\User\Models\User;

class Rating extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'utility_ratings';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.rating';
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewrateable()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
