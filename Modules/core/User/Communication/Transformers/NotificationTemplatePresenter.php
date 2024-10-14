<?php

namespace Modules\User\Communication\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class NotificationTemplatePresenter extends FractalPresenter
{

    /**
     * @return NotificationTemplateTransformer
     */
    public function getTransformer()
    {
        return new NotificationTemplateTransformer();
    }
}
