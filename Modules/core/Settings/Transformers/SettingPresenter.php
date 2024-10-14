<?php

namespace Modules\Settings\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class SettingPresenter extends FractalPresenter
{

    /**
     * @return SettingTransformer
     */
    public function getTransformer()
    {
        return new SettingTransformer();
    }
}
