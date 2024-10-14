<?php

namespace Modules\Components\LMS\Classes;

use Modules\Components\LMS\Models\UserLMS;
use Modules\Components\LMS\Models\Favourite as FavouriteModel;
use Illuminate\Support\Facades\Auth;


class Favourite
{
  /**
   * LMS constructor.
   */
  function __construct()
  {
  }

  public function check($module, $module_id, $user_id = null)
  {

    if (!Auth::check())
      return false;
    if ($user_id) {

      $user = UserLMS::find($user_id);
    } else {
      $user = UserLMS::find(Auth()->id());
    }

    $countFavourite = $user->favourites()->where('favourittable_type', $module)->where('favourittable_id', $module_id)->count();

    if ($countFavourite) {
      return true;
    }
    return false;
  }

  public function favourite($module, $module_id, $user_id)
  {

    if (!Auth::check() || !$module_id) {
      return ['success' => false, 'actionType' => 'add'];
    }


    if ($user_id) {
      $user = UserLMS::find($user_id);
    } else {
      $user = UserLMS::find(Auth()->id());
    }
    $exists = $user->favourites()->where('favourittable_type', $module)->where('favourittable_id', $module_id)->count();

    if ($exists) {
       $user->favourites()->where('favourittable_type', $module)->where('favourittable_id', $module_id)->delete();
       return ['success' => true, 'actionType' => 'remove'];
    }

    FavouriteModel::create([
      'user_id' => $user->id,
      'favourittable_type' => $module,
      'favourittable_id' => $module_id
    ]);


    return ['success' => true, 'actionType' => 'add'];
  }

}
