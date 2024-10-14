<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Foundation\Http\Controllers\PublicBaseController;
use Modules\Components\LMS\Models\Course;
use \Modules\Components\LMS\Models\StudentResult;

class HomeController extends PublicBaseController
{
     function index()
     {
          $page_title = 'الصفحة الشخصية';
          $courses = Course::where('status', '=', '1')->orderBy('created_at', 'des')->take(8)->get();


          return view('templates.home')->with(compact('courses', 'page_title'));
     }
     function search()
     {
          return abort(404);
          // return view('search');
     }

     function ajax_show_student_result($hashed_id)
     {

          $id = hashids_decode($hashed_id);

          $student = StudentResult::find($id);

          return view('templates.sections.home.ajax_show_student_results')->with(compact('student'));
     }
}
