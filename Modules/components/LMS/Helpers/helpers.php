<?php
if (!function_exists('countData')) {
    /**
     * @return mixed
     */
    function countData($data)
    {
    	if(!empty($data)){

      if(is_array($data)){
           return true; 
        }

    	  return  $data->count();
    	}

        return 0;
    }
}

    function secondsToData($seconds){
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

        return $timeFormat;

    }

function userLMS(){

            $authUser = new \Modules\Components\LMS\Models\UserLMS;
        if(Auth::check()){

        $authUser = \Modules\Components\LMS\Models\UserLMS::find(Auth()->id());

        }

        return $authUser;
}


if (!function_exists('arSlug')) {
    /**
     * @return mixed
     */
    function arSlug($name)
    {
      

        return str_replace(' ', '-', $name);
    }
}

if (!function_exists('has_live_class')) {
    /**
     * @return mixed
     */
    function has_live_class($start, $end)
    {

     return ['status' => true, 'remain' => 0, 'ended_from' => 0];
    }
}



