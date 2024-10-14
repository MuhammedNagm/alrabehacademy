<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Foundation\Http\Controllers\PublicBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class FavouritesController extends PublicBaseController
{

    public function index()
    {
    }


    //show

    public function favourite(Request $request, $module, $module_hash_id)
    {

        $module_id = hashids_decode($module_hash_id);
        if (!Auth::check()) {
            $error_type = 'user_login';
            $view = view('partials.quiz_body.templates.error')->with(compact('error_type'))->render();
            return response()->json(['success' => false, 'view' => $view]);
        }

        if(!$module_id){
            return ['success' => false, 'actionType' => 'remove'];
        }

        $response = \Favourite::favourite($module, $module_id, Auth::id());

        if ($module == 'question') {
            $view = view('components.q_favourite_action')->with(compact('module', 'module_hash_id'))->render();
        } else {
            $view = view('components.favourite_action')->with(compact('module', 'module_hash_id'))->render();
        }
        return response()->json(['success' => true, 'actionType' => $response['actionType'], 'view' => $view]);
    }
}
