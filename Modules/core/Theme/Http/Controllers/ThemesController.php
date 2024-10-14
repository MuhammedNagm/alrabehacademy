<?php

namespace Modules\Theme\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Theme\Http\Requests\ThemeRequest;


class ThemesController extends BaseController
{


    public function __construct()
    {
        $this->resource_url = config('themes.resource_url');
        $this->title = 'Theme::module.theme.title';
        $this->title_singular = 'Theme::module.theme.title_singular';

        parent::__construct();

    }

    /**
     * @param ThemeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(ThemeRequest $request)
    {

    }



}
