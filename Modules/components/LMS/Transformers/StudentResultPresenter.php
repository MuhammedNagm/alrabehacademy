<?php

namespace Modules\Components\LMS\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class StudentResultPresenter extends FractalPresenter
{

    /**
     * @return StudentResultTransformer
     */
    public function getTransformer()
    {
        return new StudentResultTransformer();
    }
}