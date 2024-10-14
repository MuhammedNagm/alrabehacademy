<?php

namespace Modules\User\Communication\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class NotificationPresenter extends FractalPresenter
{

    /**
     * @return NotificationTransformer
     */
    public function getTransformer()
    {
        return new NotificationTransformer();
    }
}
