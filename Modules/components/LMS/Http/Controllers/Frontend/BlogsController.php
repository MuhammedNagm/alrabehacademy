<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Foundation\Http\Controllers\PublicBaseController;

class BlogsController extends PublicBaseController
{
    function index(){
  
        return view('blogs.index');

    }
    function show()
    {
    	  return view('blogs.show');
    }

}