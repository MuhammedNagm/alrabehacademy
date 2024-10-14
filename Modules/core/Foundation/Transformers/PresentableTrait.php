<?php

namespace Modules\Foundation\Transformers;

use Modules\Foundation\Contracts\PresenterInterface;
use Illuminate\Support\Arr;

/**
 * Trait PresentableTrait
 * @package Modules\Foundation\Transformers
 */
trait PresentableTrait
{

    protected $presenter = null;
    protected $presenterData = null;

    /**
     * @param PresenterInterface $presenter
     * @return $this
     */
    public function setPresenter(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function present($key, $default = null)
    {
        $value = $default;

        if ($this->hasPresenter()) {
            $data = $this->presenter();

            $value = Arr::get($data, $key, $default);
        }

        if (is_null($value)) {
            $value = $this->{$key};
        }

        return $value;
    }

    /**
     * @return bool
     */
    protected function hasPresenter()
    {
        return isset($this->presenter) && $this->presenter instanceof PresenterInterface;
    }

    /**
     * @return $this|mixed
     */
    public function presenter()
    {
        if (!is_null($this->presenterData)) {
            return $this->presenterData['data'] ?? $this->presenterData;
        }

        if ($this->hasPresenter()) {
            $presenter = $this->presenter->present($this);
            $this->presenterData = $presenter;
            return $presenter['data'] ?? $presenter;
        }

        return $this;
    }
}
