<?php

namespace Modules\User\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = 'dashboard';

        $this->title = 'User::module.dashboard.title';

        $this->title_singular = 'User::module.dashboard.title_singular';

        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::check())
          return abort(404);

      $user = user();
      if(!$user->can('User::user.view_dashboard'))
        return redirect()->to(url('/'));
      
            
        $active_tab = 'dashboard';
        $active_tab = \Filters::do_filter('active_dashboard_tab', $active_tab, user());
        $dashboard_content = \Filters::do_filter('dashboard_content', '', $active_tab);
        return view('User::dashboard.user')->with(compact('dashboard_content'));
    }
}
