<?php

namespace Modules\Components\Utility\Transformers\Wishlist;

use Modules\Foundation\Transformers\FractalPresenter;

class WishlistPresenter extends FractalPresenter
{

    /**
     * @return WishlistTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WishlistTransformer();
    }
}
