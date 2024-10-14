<?php

namespace Modules\Activity\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class ActivityPresenter extends FractalPresenter
{

    /**
     * @return ActivityTransformer
     */
    public function getTransformer()
    {
        return new ActivityTransformer();
    }
}
