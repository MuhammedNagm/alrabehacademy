<?php

namespace Modules\User\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class UserPresenter extends FractalPresenter
{

    /**
     * @return UserTransformer
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}
