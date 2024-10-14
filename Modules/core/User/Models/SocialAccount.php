<?php

namespace Modules\User\Models;
use Modules\Foundation\Models\BaseModel;

class SocialAccount extends BaseModel
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
