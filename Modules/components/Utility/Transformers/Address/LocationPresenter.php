<?php

namespace Modules\Components\Utility\Transformers\Address;

use Modules\Foundation\Transformers\FractalPresenter;

class LocationPresenter extends FractalPresenter
{

    /**
     * @return LocationTransformer
     */
    public function getTransformer()
    {
        return new LocationTransformer();
    }
}
