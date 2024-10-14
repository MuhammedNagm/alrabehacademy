<?php

namespace Modules\Foundation\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $resource_url = '';
    public $title = '';
    public $title_singular = '';
    protected $modules_middleware_except = [];
    protected $modules_middleware = ['auth'];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->modules_middleware = \Filters::do_filter('modules_middleware', $this->modules_middleware, request());

        $this->middleware($this->modules_middleware, ['except' => $this->modules_middleware_except]);

        $this->middleware(function ($request, $next) {
            $this->setTheme();

            $this->setViewSharedData();

            return $next($request);
        });

    }

    public function setTheme()
    {
        \Theme::set($this->getDefaultAdminTheme());
    }

    protected function getDefaultAdminTheme()
    {
        $default_admin_theme = \Settings::get('active_admin_theme', config('themes.modules_admin'));

        if (session()->has('dashboard_theme')) {
            $default_admin_theme = session('dashboard_theme');

            $theme = \Theme::find($default_admin_theme);

            $this->loadThemeTranslations($theme);
        }

        return $default_admin_theme;
    }

    protected function loadThemeTranslations($theme)
    {
        $path = $theme->getViewPaths()[0] ?? null;

        if ($path) {
            $path .= '/lang';
            app()['translator']->addNamespace($theme->name, $path);
        }

        if (!is_null($theme->parent)) {
            $this->loadThemeTranslations($theme->parent);
        }
    }

    /**
     * set variables shared with all controller views
     * @param array $variables
     */
    protected function setViewSharedData($variables = [])
    {
        $this->title_singular = trans(array_get($variables, 'title_singular', $this->title_singular));
        $variables['title_singular'] = $this->title_singular;

        $this->title = trans(array_get($variables, 'title', $this->title));
        $variables['title'] = $this->title;

        $this->resource_url = array_get($variables, 'resource_url', $this->resource_url);
        $variables['resource_url'] = $this->resource_url;

        view()->share($variables);
    }
}
