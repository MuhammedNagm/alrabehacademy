<?php

namespace Modules\Menu\Transformers;

use Modules\Foundation\Transformers\FractalPresenter;

class MenuPresenter extends FractalPresenter
{

    /**
     * @return MenuTransformer
     */
    public function getTransformer()
    {
        return new MenuTransformer();
    }
}
