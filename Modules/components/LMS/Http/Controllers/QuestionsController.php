<?php

namespace Modules\Components\LMS\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Components\LMS\DataTables\QuestionsDataTable;
use Modules\Components\LMS\DataTables\ParagraphQuestionsDataTable;
use Modules\Foundation\Http\Requests\BulkRequest;
use Modules\Components\LMS\Http\Requests\QuestionRequest;
use Modules\Components\LMS\Models\Question;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Models\Answer;

class QuestionsController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.question.resource_url');
        $this->title = 'LMS::module.question.title';
        $this->title_singular = 'LMS::module.question.title_singular';

        parent::__construct();
    }

    /**
     * @param QuestionRequest $request
     * @param QuestionsDataTable $dataTable
     * @return mixed
     */
    public function index(QuestionRequest $request, QuestionsDataTable $dataTable)
    {
        if(!$request->ajax()){
            $session_name = 'add_bulk_questions__to_session_'.user()->hashed_id;
            if($request->session()->has($session_name)){
                $request->session()->forget($session_name);
            }
           
        }
   
        return $dataTable->render('LMS::questions.index');
    }

    /**
     * @param QuestionRequest $request
     * @return $this
     */
    public function create(QuestionRequest $request)
    {
    
        $question = new Question();

                $question_title = '';
                    if(session()->has('question_current_title')){
               $question_title = session()->get('question_current_title');
            }


        $this->setViewSharedData(['title_singular' => trans('Modules::labels.create_title', ['title' => $this->title_singular])]);

        return view('LMS::questions.create_edit')->with(compact('question', 'question_title'));
    }

    /**
     * @param QuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuestionRequest $request)
    {
        try {
            
         if(session()->has('question_current_title')){
                session()->forget('question_current_title');
            }

            session()->put('question_current_title', $request->get('title'));


            $checks = ['show_check_answer' => $request->show_check_answer?:0, 'show_question_title' => $request->show_question_title?:0,'allow_comments' => $request->allow_comments?:0];
            $request->merge($checks);

            $data = $request->except('answers','categories','quizzes','paragraph');

            $question = Question::create($data);

            $question_answers = [];

            $answers = $request->get('answers', []);

            if($request->get('question_type') != 'paragraph'){

            foreach ($answers as $answer) {
                $answer['question_id'] =  $question->id;
                $question_answer = Answer::create($answer);
                $question_answers[] = $question_answer;
            }
        }
       
       $parent_q_id = $request->get('parent_id');
        $parent_quizzes_ids = [];

         if($parent_q_id && $request->get('question_type') != 'paragraph'){

        $parent_quizzes_ids = Quiz::whereHas('questions', function ($q) use ($parent_q_id) {
            $q->where('lms_questions.id', $parent_q_id);
        })->pluck('lms_quizzes.id')->toArray();

         }

         $all_parent_quizzes_ids = array_merge($request->get('quizzes', []),$parent_quizzes_ids);

        $question->quizzes()->sync($all_parent_quizzes_ids);
             // $question->categories()->sync($request->get('categories', []));
     

            flash(trans('Modules::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Question::class, 'store');
        }

        if($qParagraph = $request->get('paragraph')){
           return redirectTo($this->resource_url.'?paragraph='.$qParagraph); 
       }else{
        return redirectTo($this->resource_url);
       }

        
    }

    /**
     * @param QuestionRequest $request
     * @param Question $question
     * @return Question
     */
    public function show(QuestionRequest $request, Question $question)
    {
        return $question;
    }

    /**
     * @param QuestionRequest $request
     * @param Question $question
     * @return $this
     */
    public function edit(QuestionRequest $request, Question $question)
    {
        $this->setViewSharedData(['title_singular' => trans('Modules::labels.update_title', ['title' => $question->title])]);

        return view('LMS::questions.create_edit')->with(compact('question'));
    }

    /**
     * @param QuestionRequest $request
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(QuestionRequest $request, Question $question)
    {
        try {

             $checks = ['show_check_answer' => $request->show_check_answer?:0, 'allow_comments' => $request->allow_comments?:0, 'show_question_title' => $request->show_question_title?:0,];
            $request->merge($checks);
            
            $data = $request->except('categories', 'answers','quizzes', 'paragraph');


            $question->update($data);

            $question->answers()->delete();
            $question_answers = [];

            $answers = $request->get('answers', []);
                        if($request->get('question_type') != 'paragraph'){

            foreach ($answers as $answer) {
                $answer['question_id'] =  $question->id;
                $question_answer = Answer::create($answer);
                $question_answers[] = $question_answer;
            }
        }

        $parent_q_id = $request->get('parent_id');
        $parent_quizzes_ids = [];

         if($parent_q_id && $request->get('question_type') != 'paragraph'){

        $parent_quizzes_ids = Quiz::whereHas('questions', function ($q) use ($parent_q_id) {
            $q->where('lms_questions.id', $parent_q_id);
        })->pluck('lms_quizzes.id')->toArray();

         }

         $all_parent_quizzes_ids = array_merge($request->get('quizzes', []),$parent_quizzes_ids);


        if($request->get('question_type') == 'paragraph'){
        // $current_quizzes = $question->quizzes()->get();
        // foreach ($current_quizzes as $quiz) {
        //     // $current_paragraph_ids = $quiz->questions()->where('parent_id', $question->id)->detach();
        // }
        $question->quizzes()->sync($all_parent_quizzes_ids);
        $selected_quizzes = Quiz::whereIn('id',  $all_parent_quizzes_ids)->get();
       $children_ids = $question->children()->pluck('lms_questions.id')->toArray();
        foreach ($selected_quizzes as $quiz) {
           $quiz->questions()->syncWithoutDetaching($children_ids);
            }

        }else{
            $question->quizzes()->sync( $all_parent_quizzes_ids);

        }



             // $question->categories()->sync($request->get('categories', []));


            flash(trans('Modules::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Question::class, 'update');
        }

                if($qParagraph = $request->get('paragraph')){
           return redirectTo($this->resource_url.'?paragraph='.$qParagraph); 
       }else{
        return redirectTo($this->resource_url);
       }

    }

    /**
     * @param QuestionRequest $request
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(QuestionRequest $request, Question $question)
    {
        try {
            $question->studentLogs()->delete();
            $question->delete();

            $message = ['level' => 'success', 'message' => trans('Modules::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Question::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param QuestionRequest $request
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(BulkRequest $request)
    {
        try {

            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            $selections_ids = [];
            foreach ($selection as $hash_id) {
               $selections_ids[] = hashids_decode($hash_id);
            }
            switch ($action) {
            case 'delete':
            foreach ($selection as $selection_id) {
                $question = Question::findByHash($selection_id);

                        $question_request = new QuestionRequest;
                        $question_request->setMethod('DELETE');
                        $this->destroy($question_request, $question);
                        $message = ['level' => 'success', 'message' => trans('Modules::messages.success.deleted', ['item' => $this->title_singular])];


                }

                break; //delete
                     case 'add_to_session':
                     $session_name = 'add_bulk_questions__to_session_'.user()->hashed_id;
                     $new_session_questions = $selections_ids;  
                     if($request->session()->has($session_name)){
                        $session_questions = $request->session()->get($session_name);
                        if(is_array($session_questions)){
                          $new_session_questions =  array_merge($session_questions, $selections_ids);
                            
                        }
                       
                     }

                    $request->session()->put($session_name, $new_session_questions);

                     $message = ['level' => 'success', 'message' => 'تم اضافة الاسئلة الى الذاكرة'];

                 break;
            }


        } catch (\Exception $exception) {
            log_exception($exception, Question::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

}