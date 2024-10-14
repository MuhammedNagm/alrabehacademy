<?php

namespace Modules\Components\LMS\Models;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;


class EmbededMedia extends BaseModel
{
    use PresentableTrait;

    /**
     *  Model configuration.
     * @var string
     */


    protected $table = "lms_embeded_media";


    protected $guarded = ['id'];




    public function mediable()
    {

        return $this->morphTo();
    }





}
