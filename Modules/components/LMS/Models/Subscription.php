<?php

namespace Modules\Components\LMS\Models;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;


class Subscription extends BaseModel
{
    use PresentableTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'lms.models.subscriptions';
    protected $table = "lms_subscriptions";
//    protected static $logAttributes = [];


    protected $guarded = ['id'];




    public function subscriptionnable()
    {

        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(UserLMS::class, 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }




}
