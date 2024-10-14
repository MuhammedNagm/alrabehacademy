<?php

namespace Modules\Settings\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class CustomFieldSettingPresenter extends FractalPresenter
{

    /**
     * @return CustomFieldSettingTransformer
     */
    public function getTransformer()
    {
        return new CustomFieldSettingTransformer();
    }
}
