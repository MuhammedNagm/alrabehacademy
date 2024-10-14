<?php

namespace Modules\User\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class RolePresenter extends FractalPresenter
{

    /**
     * @return RoleTransformer
     */
    public function getTransformer()
    {
        return new RoleTransformer();
    }
}
