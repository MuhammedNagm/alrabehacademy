<?php

namespace Modules\Components\LMS\Http\Controllers;

use Illuminate\Support\Str;
use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Components\LMS\DataTables\QuizzesDataTable;
use Modules\Components\LMS\Http\Requests\QuizRequest;
use Illuminate\Support\Facades\DB;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Models\Tag;
use Modules\Components\LMS\Models\Question;
use Modules\Components\LMS\Models\Answer;
use Modules\Components\LMS\Models\EmbededMedia;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class QuizzesController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.quiz.resource_url');
        $this->title = 'LMS::module.quiz.title';
        $this->title_singular = 'LMS::module.quiz.title_singular';

        parent::__construct();
    }

    /**
     * @param QuizRequest $request
     * @param QuizzesDataTable $dataTable
     * @return mixed
     */
    public function index(QuizRequest $request, QuizzesDataTable $dataTable)
    {
        return $dataTable->render('LMS::quizzes.index');
    }

    /**
     * @param QuizRequest $request
     * @return $this
     */
    public function create(QuizRequest $request)
    {

        $quiz = new Quiz();
        $sub_quiz =  $quiz;


        $quiz_session_id = \LMS::codeGenerator(5, true ,'quiz_',user()->hashed_id);

        $this->setViewSharedData(['title_singular' => trans('Modules::labels.create_title', ['title' => $this->title_singular])]);

        return view('LMS::quizzes.create_edit')->with(compact('quiz', 'quiz_session_id','sub_quiz'));
    }

    /**
     * @param QuizRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuizRequest $request)
    {

        try {
            $checks = ['numbers_in_arabic' => $request->numbers_in_arabic?:0, 'preview' => $request->preview?:0, 'pagination_questions' => $request->pagination_questions?:0, 'review_questions' => $request->review_questions?:0, 'show_check_answer' => $request->show_check_answer?:0, 'skip_question' => $request->skip_question?:0, 'show_hint' => $request->show_hint?:0, 'allow_comments' => $request->allow_comments?:0, 'status' => $request->status?:0, 'private' => $request->private?:0,'is_featured' => $request->is_featured?:0,'is_standlone' => $request->is_standlone?:0,'show_questions_title' => $request->show_questions_title?:0];

          $request->merge($checks);
            $data = $request->except('arr_questions_ids', 'questions','categories', 'tags', 'thumbnail', 'quiz_session_id','sub_quiz','paragraphs','create_new_paragraph','create_paragraph','quiz_videos');
            $data['is_sub_quiz'] = false;

            $quiz = Quiz::create($data);


            if ($request->hasFile('thumbnail')) {
               $quiz->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($quiz->mediaCollectionName);
            }

            // $q_ids = [];
            // $orders = [];

            // $arr_questions_ids = json_decode("[".$request->arr_questions_ids."]");

            // foreach ($arr_questions_ids as $index => $id) {
            //     $q_ids[] = $id;
            //     $orders[] = ['order' => $index];
            // }

          $videos = $request->get('quiz_videos', []);

            foreach ($videos as $video) {
                $video['mediable_type'] =  'quiz';
                 $video['mediable_id'] =  $quiz->id;
               EmbededMedia::create($video);
            }


          $selected_questions = Question::whereIn('id', $request->input('paragraphs', []))->get();
          $q_ids = [];

            foreach ($selected_questions as $question) {
              $q_ids[] = $question->id;
                $children_ids = $question->children()->pluck('lms_questions.id')->toArray();
                $q_ids = array_merge($q_ids, $children_ids);
            }


            if($request->get('create_new_paragraph')){
              $newParagraphRequest = $request->get('create_paragraph',[]);
              $newParagraphRequest['status'] = $request->status;
             $newCreatedParagraph = Question::create($request->get('create_paragraph'));

             $q_ids[] = $newCreatedParagraph->id;
            }

            $quiz->questions()->syncWithoutDetaching($q_ids);
            $quiz->categories()->sync(array_filter($request->input('categories', [])));
            // if($qQuestions = $quiz->questions()){

            // $totalQuestionsDegree = $qQuestions->sum('points');

            // $quiz->update(['total_degree' =>  $totalQuestionsDegree]);

            // }

            $sub_quiz = $this->store_update_sub_quiz($request, $quiz->id, $data);




           $tags = $this->getTags($request);

            $quiz->tags()->sync($tags);


            flash(trans('Modules::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Quiz::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return Quiz
     */
    public function show(QuizRequest $request, Quiz $quiz)
    {
        return $quiz;
    }

    /**
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return $this
     */
    public function edit(QuizRequest $request, Quiz $quiz)
    {
        $quiz_session_id = \LMS::codeGenerator(5, true ,'quiz_'.$quiz->hashed_id,user()->hashed_id);
         if(session()->has('quiz_session_'.$quiz_session_id)){
                session()->forget('quiz_session_'.$quiz_session_id);
            }
        $this->setViewSharedData(['title_singular' => trans('Modules::labels.update_title', ['title' => $quiz->title])]);

        if($quiz->questions){
           $questions = $quiz->questions->pluck('id')->toArray();
        session()->put('quiz_session_'.$quiz_session_id, ['questions' => $questions]);
        }

        $sub_quiz = $quiz->children()->first();

        return view('LMS::quizzes.create_edit')->with(compact('quiz', 'quiz_session_id','sub_quiz'));
    }

    /**
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        try {

          $checks = ['numbers_in_arabic' => $request->numbers_in_arabic?:0,'preview' => $request->preview?:0, 'pagination_questions' => $request->pagination_questions?:0, 'review_questions' => $request->review_questions?:0, 'show_check_answer' => $request->show_check_answer?:0, 'skip_question' => $request->skip_question?:0, 'show_hint' => $request->show_hint?:0, 'allow_comments' => $request->allow_comments?:0, 'status' => $request->status?:0, 'private' => $request->private?:0,'is_featured' => $request->get('is_featured')?true:false,'is_standlone' => $request->is_standlone?:0, 'show_questions_title' => $request->show_questions_title?:0];

          $request->merge($checks);
            $data = $request->except('arr_questions_ids','questions','categories', 'tags', 'thumbnail', 'clear', 'quiz_session_id','sub_quiz','paragraphs','create_new_paragraph','create_paragraph','quiz_videos');
            $data['is_sub_quiz'] = false;

            $current_paragraph_ids = $quiz->questions()->where('question_type', 'paragraph')->pluck('lms_questions.id')->toArray();
            $current_para_child_ids = Question::whereIn('parent_id', $current_paragraph_ids)->pluck('lms_questions.id')->toArray();
            $detachs = array_merge($current_paragraph_ids, $current_para_child_ids);
            $quiz->questions()->detach($detachs);

            $quiz->update($data);



         $quiz->hint_videos()->delete();

          $videos = $request->get('quiz_videos', []);
            foreach ($videos as $video) {
                $source = Str::contains($video['file_url'], ['youtube.com', 'youtu.be'])? "youtube":'unknown';
                $video['mediable_type'] =  'quiz';
                $video['mediable_id'] =  $quiz->id;
                $video['source'] =  $source;
                $video['type'] =  'video';

                EmbededMedia::create($video);
            }


         if ($request->has('clear') || $request->hasFile('thumbnail')) {
                $quiz->clearMediaCollection($quiz->mediaCollectionName);
            }

              if ($request->hasFile('thumbnail')) {
                $quiz->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($quiz->mediaCollectionName);
               }

            // $q_ids = [];
            // $orders = [];

            // $arr_questions_ids = json_decode("[".$request->arr_questions_ids."]");

            // foreach ($arr_questions_ids as $index => $id) {
            //     $q_ids[] = $id;
            //     $orders[] = ['order' => $index];
            // }
            // $quiz->questions()->sync(array_combine($q_ids, $orders));

          $selected_questions = Question::whereIn('id', $request->input('paragraphs', []))->get();
          $q_ids = [];

            foreach ($selected_questions as $question) {
              $q_ids[] = $question->id;
                $children_ids = $question->children()->pluck('lms_questions.id')->toArray();
                $q_ids = array_merge($q_ids, $children_ids);
            }

            if($request->get('create_new_paragraph')){
              $newParagraphRequest = $request->get('create_paragraph',[]);
              $newParagraphRequest['status'] = $request->status;
             $newCreatedParagraph = Question::create($request->get('create_paragraph'));

             $q_ids[] = $newCreatedParagraph->id;
            }

            $quiz->questions()->syncWithoutDetaching($q_ids);
            $quiz->categories()->sync(array_filter($request->input('categories', [])));


           $sub_quiz = $this->store_update_sub_quiz($request, $quiz->id, $data);



            // if($qQuestions = $quiz->questions()){

            // $totalQuestionsDegree = $qQuestions->sum('points');

            // $quiz->update(['total_degree' =>  $totalQuestionsDegree]);

            // }

             $tags = $this->getTags($request);

            $quiz->tags()->sync($tags);

        if ($request->session()->has('questions_quiz_session_' . $quiz->hashed_id)) {
            $request->session()->forget('questions_quiz_session_' . $quiz->hashed_id);
        }


            flash(trans('Modules::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Quiz::class, 'update');
        }



        return redirectTo($this->resource_url);
    }

        /**
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return \Illuminate\Http\JsonResponse
     */

        private function store_update_sub_quiz($request, $quiz_id, $data = [])
    {
         $quiz = Quiz::find($quiz_id);
                $sub_quiz = $quiz->children()->first();

            $sub_quiz_request = $request->sub_quiz;

            if(isset($sub_quiz_request['sub_quiz_questions_num']) && $sub_quiz_request['sub_quiz_questions_num'] > 0){
                $sub_quiz_request['title'] = '[اختبر نفسك ] ' . $data['title'];
                $sub_quiz_request['parent_id'] = $quiz->id;
                 $sub_quiz_request['is_sub_quiz'] = true;
                 $sub_quiz_request['retake_count'] = 100000;



                $sub_data = array_merge($data, $sub_quiz_request);
                if($sub_quiz){
                    $sub_quiz->update($sub_data);
                }else{
                   $sub_quiz = Quiz::create($sub_data);

                }

                $arr_questions_paragraph_ids = $quiz->questions->where('lms_questions.question_type','paragraph')->pluck('id')->toArray();


                if($sub_quiz->sub_quiz_random > 0){


                 $arr_questions_ids = $quiz->questions()->orderBy('lms_questions.parent_id', 'asc')->inRandomOrder()->limit($sub_quiz->sub_quiz_questions_num)->pluck('lms_questions.id')->toArray();


                }else{

                $arr_questions_ids = $quiz->questions()->orderBy('lms_questions.parent_id', 'asc')->orderBy('lms_quiz_questions.order', 'asc')->take($sub_quiz->sub_quiz_questions_num)->pluck('lms_questions.id')->toArray();

                }


                $arr_questions_ids =  array_merge($arr_questions_ids, $arr_questions_paragraph_ids);



            $q_ids = [];
            $orders = [];

            foreach ($arr_questions_ids as $index => $id) {
                $q_ids[] = $id;
                $orders[] = ['order' => $index];
            }
            $sub_quiz->questions()->sync(array_combine($q_ids, $orders));

            }

            return $sub_quiz;

    }

    /**
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return \Illuminate\Http\JsonResponse
     */

        private function getTags($request)
    {
        $tags = [];

        $requestTags = $request->get('tags', []);

        foreach ($requestTags as $tag) {
            if (is_numeric($tag)) {
                array_push($tags, $tag);
            } else {
                try {
                    $newTag = Tag::create([
                        'name' => $tag,
                        'slug' => str_slug($tag)
                    ]);

                    array_push($tags, $newTag->id);
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }

        return $tags;
    }

    public function destroy(QuizRequest $request, Quiz $quiz)
    {
        try {
        $allQuizStudentLogs = $quiz->studentLogs()->get();
       if($allQuizStudentLogs->count()){
        foreach ($allQuizStudentLogs as $rowLog) {
            $rowLog->children()->delete();
        }
       }
        $quiz->studentLogs()->delete();

            $quiz->delete();

            $message = ['level' => 'success', 'message' => trans('Modules::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Quiz::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

            /**
     * @param QuizRequest $request
     * @return $this
     */
    public function delete_options(QuizRequest $request, Quiz $quiz)
    {

        $this->setViewSharedData(['title_singular' => trans('Modules::labels.delete')]);

        return view('LMS::quizzes.partials.delete')->with(compact('quiz'));
    }

                /**
     * @param QuizRequest $request
     * @return $this
     */
    public function show_quizzes_select2_list(Request $request)
    {
      $quiz = new Quiz;
      $no_questions = true;
        $session_name = 'add_bulk_questions__to_session_'.user()->hashed_id;
       if(!$request->session()->has($session_name) && empty($request->session()->get($session_name))){
        $no_questions = false;

         }else{
            $count_questions = count($request->session()->get($session_name));

         }


        return view('LMS::quizzes.partials.show_quizzes_select2_list')->with(compact('quiz', 'no_questions','count_questions'));
    }

                    /**
     * @param QuizRequest $request
     * @return $this
     */
    public function session_questions_to_quiz(Request $request)
    {
      $id = $request->quiz_id;
      $session_name = 'add_bulk_questions__to_session_'.user()->hashed_id;

      if(!$id){

        flash('لم تقوم باختيار الاختبار')->error();

          return redirectTo(url('/lms/questions'));

         }
        $quiz = Quiz::find($id);
          if(!$quiz){

        flash('الاختبار غير موجود')->error();

          return redirectTo(url('/lms/questions'));

         }

            $q_ids = [];
            $orders = [];
            $arr_questions_ids = [];

            if($request->session()->has($session_name)){

             $arr_questions_ids = $request->session()->get($session_name);
             $selected_questions = Question::whereIn('id', $arr_questions_ids)->get();

             $count_questions = $selected_questions->count();

             if($count_questions){
              $index = 1;

              $last_child_count = 0;

            foreach ($selected_questions as $question) {
              $plus_index = $index++;
              $q_ids[] = $question->id;
                $orders[] = ['order' => $count_questions + $plus_index + $last_child_count];
              if($question->question_type == 'paragraph'){
                $children_ids = $question->children()->pluck('lms_questions.id')->toArray();
                $last_child_count += count($children_ids);
                $q_ids = array_merge($q_ids,$children_ids);
                foreach ($children_ids as $index => $value) {
                 $orders[] = ['order' => $count_questions + $index + 1 + $plus_index];
                }

              }
            }

          }
            $quiz->questions()->syncWithoutDetaching(array_combine($q_ids, $orders));
            $request->session()->forget($session_name);

            }

          flash('تم اضافة الاسئلة الى الاختبار')->success();


          return redirectTo(url('/lms/questions'));

    }



                /**
     * @param QuizRequest $request
     * @return $this
     */
    public function delete_quiz(Request $request, $hashed_id)
    {
    if (!user()->hasPermissionTo('LMS::quiz.delete')) {
            return abort(403);
        }
        try {
        $this->validate($request, ['type' => 'required']);

        $id = hashids_decode($hashed_id);
         $quiz = Quiz::find($id);

        if (empty($quiz)) {
            abort(404);
        }

       $allQuizStudentLogs = $quiz->studentLogs()->get();
       if($allQuizStudentLogs->count()){
        foreach ($allQuizStudentLogs as $rowLog) {
            $rowLog->children()->delete();
        }
       }
        $quiz->studentLogs()->delete();


        if($request->get('type') == 'only_quiz'){
            $quiz->delete();
        }else{
            if($quiz->is_sub_quiz > 0){
             $quiz->delete();
            }else{
            $quiz->questions()->delete();
            $quiz->delete();

            }


        }

     flash(trans('Modules::messages.success.delete', ['item' => $this->title_singular]))->success();
             } catch (\Exception $exception) {
            log_exception($exception, Quiz::class, 'destroy');
        }

                return redirectTo($this->resource_url);

    }

                /**
     * @param QuizRequest $request
     * @return $this
     */
    public function clone_quiz(Request $request, $hashed_id)
    {

      if (!user()->hasPermissionTo('LMS::quiz.create')) {
            flash('ليس لديك الصلاحيات لنسخ اختبار')->error();

          return redirectTo(url()->previous());
        }
        $id = hashids_decode($hashed_id);
         $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            abort(404);
        }



        $arr_questions_ids = $quiz->questions()->whereNotNull('lms_questions.id')->orderBy('parent_id', 'asc')->orderBy('lms_quiz_questions.order', 'asc')->pluck('lms_questions.id')->toArray();
        $files = $quiz->files()->whereNotNull('lms_embeded_media.id')->get();

        $newQuiz = $quiz->replicate();
        $newQuiz->title = $quiz->title.  '(منسوخ)';
        $newQuiz->slug = $quiz->slug. uniqid();
        $newQuiz->save();

            $q_ids = [];
            $orders = [];

            foreach ($arr_questions_ids as $index => $id) {
                $q_ids[] = $id;
                $orders[] = ['order' => $index];
            }
            $newQuiz->questions()->sync(array_combine($q_ids, $orders));
            foreach ($files as $file) {
               $file->replicate();
               $file->mediable_id = $newQuiz->id;
               $file->save();

            }

      $pictures = \DB::table('media')->where('model_type', 'quiz')->where('model_id', $quiz->id)->where('collection_name', $quiz->mediaCollectionName)->get();

            if($pictures->count()){
             $pictures =   $pictures->toArray();
             $newPictures = $pictures[0];
             $newPictures['model_id'] = $newQuiz->id;
             \DB::table('media')->insert($newPictures);

            }

          flash('تم النسخ بنجاح ')->success();
        return redirectTo($this->resource_url.'/'.$newQuiz->hashed_id.'/edit');
    }


}
