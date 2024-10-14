<?php
 
namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Foundation\Http\Controllers\PublicBaseController;

class LessonsController extends PublicBaseController
{
    function index(){
  
        return view('courses.lesson');

    }
   function quiz(){

     return view('courses.quiz');

    }

    function QuizResult(){

     return view('courses.quiz_result');

    }


}