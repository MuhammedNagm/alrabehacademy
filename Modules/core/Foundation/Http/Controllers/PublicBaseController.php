<?php

namespace Modules\Foundation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicBaseController extends BaseController
{
    private $user ;
    /**
     * PublicBaseController constructor.
     */
    public function __construct()
    {
        $this->modules_middleware = [];
        $this->modules_middleware_except = [];
  



        parent::__construct();
    }

    public function setTheme()
    {
        \Theme::set(\Settings::get('active_frontend_theme', config('themes.modules_frontend')));
    }

    public function welcome(Request $request)
    {
        return view('welcome');
    }
}
