<?php

namespace Modules\Foundation\Contracts;

/**
 * Interface PresenterInterface
 * @package Modules\Foundation\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
