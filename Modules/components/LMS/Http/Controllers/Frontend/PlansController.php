<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Components\LMS\Models\Plan;
use Modules\Components\LMS\Models\Category;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Models\Course;
use Modules\Components\LMS\Models\UserLMS;
use Modules\Foundation\Http\Controllers\PublicBaseController;
use Modules\Components\CMS\Traits\SEOTools;
use Illuminate\Http\Request;

class PlansController extends PublicBaseController
{
   use SEOTools;

    public function __construct()
    {

        parent::__construct();

    }


    function index()
    {
        // all plans  
        $plans = Plan::where('status', 1);

        return view('plans.index')->with(compact('plans'));


    }

        function show($hashed_id)
    {

      $id = hashids_decode($hashed_id);

        $plan = Plan::where('id', $id)->with(['categories'])->first();
        if(!$plan){
            return abort(404);
        }
        $categories = $plan->categories;

        $page_title = $plan->title;
        $item = [
            'title'            => $plan->title,
            'meta_description' => str_limit(strip_tags($plan->meta_description), 500),
            'url'              => route('courses.show', $plan->hashed_id),
            'type'             => 'course',
            'image'            => $plan->thumbnail,
            'meta_keywords'    => $plan->meta_keywords
        ];

        $this->setSEO((object)$item);


        $subscriptionStatus = ['success' => false, 'status' => true];

        if(auth()->user()){
            $user = UserLMS::find(Auth()->id());

        $moduleArray = [
            'module'    => 'plan',
            'module_id' => $id,
            'user'      => $user,
            'parent'    => [],

        ];

        $subscriptionStatus = \Subscriptions::check_subscription($moduleArray);

        }



        return view('plans.show')->with(compact('page_title'
        , 'plan','categories', 'subscriptionStatus'));
    }



    function quiz_show($plan_hashed_id, $quiz_hashed_id)
    {
       $id = hashids_decode($quiz_hashed_id);

        $quiz = Quiz::find($id);

        if (empty($quiz)) {
            abort(404);
        }

        $page_title = $quiz->title;
        $item = [
            'title'            => $quiz->title,
            'meta_description' => str_limit(strip_tags($quiz->meta_description), 500),
            'url'              => route('courses.show', $quiz->hashed_id),
            'type'             => 'quiz',
            'image'            => $quiz->thumbnail,
            'meta_keywords'    => $quiz->meta_keywords
        ];

        $this->setSEO((object)$item);

        /*******  Related Courses*********/
        $relatedIds = $quiz->categories->pluck('id')->toArray();
        $relatedQuizzes = Quiz::whereHas('categories', function ($q) use ($relatedIds) {
            $q->whereIn('id', $relatedIds);
        })->where('status', true);
        /******* Side bar*********/
        $user = null;
        $subscriptionStatus = ['success' => false, 'status' => 0, 'message' => 'not subscribed'];
        $enroll_status = false;

        if (!user()) {
            return view('quizzes.show')->with(compact('quiz',
                'relatedQuizzes',
                'subscriptionStatus'

            ));
        }


        $user = UserLMS::find(Auth()->id());

        $moduleArray = [
            'module'    => 'quiz',
            'module_id' => $id,
            'user'      => $user,
            'parent'    => [],

        ];

        $subscriptionStatus = \Subscriptions::check_subscription($moduleArray);

        // if($subscriptionStatus['success'] && $subscriptionStatus['status'] > 0){
        //     \Logs::enroll($moduleArray);

        // }

        return view('quizzes.show')->with(compact('quiz',
            'relatedQuizzes',
            'subscriptionStatus'

        ));
    }

    function category($plan_hashed_id, $category_hashed_id)
    {
        $plan_id = hashids_decode($plan_hashed_id);
        $category_id = hashids_decode($category_hashed_id);
        if(!auth()->check()){
            return redirect()->back()->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);
        }
        $plan = Plan::with('categories')->find($plan_id);
        if(!$plan){
            return abort(404);
        }

        $category = Category::where('id', $category_id)->with(['childrenCategories', 'courses', 'quizzes', 'books'])->first();


        if(!$category){
            return abort(404);
        }

        $user = UserLMS::find(Auth()->id());


        $moduleArray = [
            'module'    => 'plan',
            'module_id' =>  $plan->id,
            'user'      => $user,
            'parent'    => [],

        ];

        $subscriptionStatus = \Subscriptions::check_subscription($moduleArray);
        if(!$subscriptionStatus['success'] && !$subscriptionStatus['status'] == 1){
            return redirect()->route('plans.show', ['plan' => $plan->hashed_id])->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);

        }


$planCategoriesIds = [];   
    $planDirectCategories  = $plan->categories()->where('lms_categories.status',1)->get();
    if($planDirectCategories->count()){
     foreach ($planDirectCategories as $rowCat) {

        $categoryIds = $rowCat->getDescendants($rowCat);
        $planCategoriesIds = array_merge($planCategoriesIds, $categoryIds);

      }  
    }

    if(!in_array($category->id, $planCategoriesIds)){
        return redirect()->back()->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);
    }



        $child_categories = $category->childrenCategories()->where('status',1)->get();
        $page_title = $category->name;
        $item = [
            'title'            => $category->name.'-'.$plan->name,
            'meta_description' => str_limit(strip_tags($category->meta_description), 500),
            'url'              => route('categories.show', $category->hashed_id),
            'type'             => 'course',
            'image'            => $category->thumbnail,
            'meta_keywords'    => $category->meta_keywords
        ];

        $this->setSEO((object)$item);

        $courses = $category->courses()->where('status', true)->paginate(5, ['*'], 'courses');

        $quizzes = $category->quizzes()->where([['status', true], ['is_standlone', true]])->paginate(5, ['*'], 'quizzes');

        $books = $category->books()->where('status', true)->paginate(5, ['*'], 'books');


        




        return view('plans.category')->with(compact('courses', 'quizzes','books','plan', 'page_title'
        , 'category','child_categories'));

    }

    

function search_in_plan(Request $request, $hashed_id)
    {
        $id = hashids_decode($hashed_id);
        $plan = Plan::find($id);
        
        $moduleCategoriesData = $plan->categories()->get();
        $moduleCategoriesIds = $moduleCategoriesData->pluck('id')->toArray();
            if($moduleCategoriesData->count()){
                 foreach ($moduleCategoriesData as $rowCat) {
                   $parentCategoryIds = $rowCat->getDescendants($rowCat);
                   $moduleCategoriesIds = array_merge($moduleCategoriesIds, $parentCategoryIds);
                    }
                   }
    $returnData = [];
     $quizzesIds = Quiz::whereHas('categories', function ($q) use ($moduleCategoriesIds) {
            $q->whereIn('id', $moduleCategoriesIds);
        })->where('title', 'like', '%' . $request->get('term') . '%')->pluck('title','id')->toArray();
     foreach ($quizzesIds as $key => $value) {
         $returnData[] = ['id' => url('/quizzes/'.hashids_encode($key).'/show'), 'text' => $value.' '.'- في الاختبارات'];
     }
    $coursesIds = Course::whereHas('categories', function ($q) use ($moduleCategoriesIds) {
            $q->whereIn('id', $moduleCategoriesIds);
        })->where('title', 'like', '%' . $request->get('term') . '%')->pluck('title','id')->toArray();

         foreach ($coursesIds as $key => $value) {
         $returnData[] = ['id' => url('/courses/'.hashids_encode($key).'/show'), 'text' => $value.' '.'- في الدورات التدريبية'];
     }


     
        return json_encode($returnData);

    }


    function plan_content(Request $request, $hashed_id)
    {
      $id = hashids_decode($hashed_id);

        $plan = Plan::where('id', $id)->with(['categories'])->first();
        if(!$plan){
            return abort(404);
        }

        if(!auth()->check()){
            return redirect()->back()->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);
        }

        $user = UserLMS::find(user()->id);

        $moduleArray = [
            'module'    => 'plan',
            'module_id' => $id,
            'user'      => $user,
            'parent'    => [],

        ];

        $subscriptionStatus = \Subscriptions::check_subscription($moduleArray);
        if(!$subscriptionStatus['success'] && !$subscriptionStatus['status'] == 1){
            return redirect()->route('plans.show', ['plan' => $plan->hashed_id])->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);

        }

        $categories = $plan->categories;

        $page_title = $plan->title;
        $item = [
            'title'            => 'محتوى '.$plan->title,
            'meta_description' => str_limit(strip_tags($plan->meta_description), 500),
            'url'              => route('plans.plan_content', $plan->hashed_id),
            'type'             => 'course',
            'image'            => $plan->thumbnail,
            'meta_keywords'    => $plan->meta_keywords
        ];

        $this->setSEO((object)$item);

        $courses = $plan->courses()->where('lms_courses.status','>', 0)->paginate(5, ['*'], 'courses');

        $quizzes = $plan->quizzes()->where('lms_quizzes.status','>', 0)->paginate(5, ['*'], 'quizzes');

        $books = $plan->books()->where('lms_books.status','>', 0)->paginate(5, ['*'], 'books');

        $user = UserLMS::find(Auth()->id());


        return view('plans.plan_content')->with(compact('courses', 'quizzes','books', 'page_title'
        , 'plan', 'categories','subscriptionStatus'));
    }



   


}
