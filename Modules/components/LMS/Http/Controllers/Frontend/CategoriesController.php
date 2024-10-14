<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Components\CMS\Traits\SEOTools;
use Modules\Components\LMS\Models\Category;
use Modules\Foundation\Http\Controllers\PublicBaseController;


class CategoriesController extends PublicBaseController
{
    use SEOTools;

    function index()
    {

        $page_title = \LMS::setGeneralPagesTitle('categories');

        \LMS::setGeneralPagesSeo('categories', route('categories.index'), null, 'categories');

        $categories = category::where('status', '=', 'active')->where('parent_id', null)->get();


        return view('courses.categories')->with(compact('categories', 'page_title'));
    }

    function show($category_hashed_id)
    {


        $id = hashids_decode($category_hashed_id);

        $category = Category::find($id);
        if(!$category){
            return abort(404);
        }

        if($category->show_in_plan > 0){
             return redirect()->back()->with(['message' => __('LMS::messages.cannot_show_page'), 'alert_type' => 'danger']);
        }

        $child_categories = Category::where('parent_id',$id)->get();

        $page_title = $category->name;
        $item = [
            'title'            => $category->name,
            'meta_description' => str_limit(strip_tags($category->meta_description), 500),
            'url'              => route('courses.show', $category->hashed_id),
            'type'             => 'course',
            'image'            => $category->thumbnail,
            'meta_keywords'    => $category->meta_keywords
        ];

        $this->setSEO((object)$item);

        $plans = $category->child_plans()->where('status', true)->paginate(6, ['*'], 'packages');

        $courses = $category->courses()->where('status', true)->paginate(5, ['*'], 'courses');

        $quizzes = $category->quizzes()->where([['status', true], ['is_standlone', true]])->paginate(5, ['*'], 'quizzes');

        $books = $category->books()->where('status', true)->paginate(5, ['*'], 'books');


        return view('courses.category')->with(compact('courses', 'quizzes', 'page_title'
        , 'category','child_categories', 'books', 'plans'));

    }
}
