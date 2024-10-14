<?php

namespace Modules\Components\LMS\Http\Controllers\Frontend;

use Modules\Components\LMS\Models\Plan;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Models\UserLMS;
use Modules\Foundation\Http\Controllers\PublicBaseController;
use Modules\Components\LMS\Http\Controllers\Frontend\QuizzesController;
use Modules\Components\CMS\Traits\SEOTools;
use Illuminate\Http\Request;

class PlansQuizzesController extends PublicBaseController
{
   use SEOTools;

    public function __construct()
    {

        parent::__construct();

    }


    function index()
    {

        // all plans  
        $plans = Plan::where('status', 1)->with('courses')->with('quizzes')->with('categories');

        return view('plans.index')->with(compact('plans'));


    }

    function show($plan_hashed_id, $quiz_hashed_id)
    {
        $quizController =  new QuizzesController;
        $quizPageUrl = route('plans.quizzes.quizPage',['plan' => $plan_hashed_id, 'quiz' => $quiz_hashed_id]);

        return $quizController->show($quiz_hashed_id, 'packages', $plan_hashed_id, null, null, $quizPageUrl);

    }

   public function show_questions($hashed_id)
    {

    }


 public function quizPage(Request $request, $plan_hashed_id, $quiz_hashed_id, $course_hashed_id = null, $retake_quiz = null)
    {
        
        $quizController =  new QuizzesController;
        $quizPageUrl = route('plans.quizzes.quizPage',['plan' => $plan_hashed_id, 'quiz' => $quiz_hashed_id]);

        return $quizController->quizPage($request,$quiz_hashed_id, $course_hashed_id, $retake_quiz,'packages',$plan_hashed_id);

    }


    public function handel_quiz(Request $request, $quiz_hashed_id)
    {


        $quiz_id = hashids_decode($quiz_hashed_id);

        $quiz = Quiz::find($quiz_id);


        if (!$quiz) {
            abort(404);
        }


        return view('quizzes.quiz')->with(compact('quiz'));

    }

    public function getQuestions(Request $request, $quiz_hashed_id, $logs_hashed_id)
    {

    $quiz_id = hashids_decode($quiz_hashed_id);
        $log_id = hashids_decode($logs_hashed_id);

        $quiz = Quiz::find($quiz_id);

        if (empty($quiz)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $quizLogs = StudentLogs::find($log_id);


        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $course = null;

        if ($quizLogs->parent_id) {
            $course = Course::find($quizLogs->parent_id);
            if (empty($course)) {
                $message = __('LMS::messages.something_happen');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();
            }
        }


        $showAnswer = $quizLogs->status == 1 ? true : false;

        $questions = $quiz->questions()->where('status', 1)->where('question_type', '!=', 'paragraph')->orderBy('parent_id', 'asc')->orderBy('pivot_order', 'asc')->paginate($quiz->question_per_page ?: 1);
        // if ($request->get('show_answer')) {
        //   if($quizLogs->status == 1){
        //     $showAnswer = true;
        //   }else{
        //      $showAnswer = false;
        //   }

        // }

        if ($request->ajax() && $request->get('page')) {

            $view = view('partials.quiz_body.templates.questions')->with(compact('quizLogs', 'quiz', 'questions', 'showAnswer', 'course'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $quizTemplate = 'questions';

        $view = view('partials.quiz_body.index')->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'showAnswer', 'course'))->render();

        return response()->json(['success' => true, 'view' => $view]);


    }

    public function show_result(Request $request, $quiz_hashed_id, $log_hashed_id)
    {

        $logs_id = hashids_decode($log_hashed_id);
        $quiz_id = hashids_decode($quiz_hashed_id);

        $quiz = Quiz::find($quiz_id);
        if (!Auth::check()) {
            $message = __('LMS::messages.ajax_must_login');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }
        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }

        $quizLogs = StudentLogs::find($logs_id);

        if (!$quizLogs) {
            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }
        $questions = $quiz->questions()->where('status', 1)->where('question_type', '!=', 'paragraph')->orderBy('parent_id', 'asc')->orderBy('pivot_order', 'asc')->paginate($quiz->question_per_page ?: 1);
        $course = null;
        if ($request->get('course_id')) {
            $course_id = hashids_decode($request->get('course_id'));
            $course = Course::find($course_id);
            if (!$course) {
                $message = __('LMS::messages.something_happen');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                return response()->json(['success' => false, 'view' => $view]);
            }
        }


        $view = view('partials.quiz_body.templates.quiz_result')->with(compact('quiz', 'quizLogs', 'questions', 'quizTemplate', 'course'))->render();

        return response()->json(['success' => true, 'view' => $view]);


    }


    public function enrollQuiz(Request $request, $quiz_hashed_id, $course_hashed_id = null)
    {

        // $startQuiz = $request->get('start_quiz');
        $course_id = hashids_decode($request->get('course_id'));
        $quiz_id = hashids_decode($quiz_hashed_id);
        //check if Lesson is privet or not
        if (!Auth::check()) {
            $message = __('LMS::messages.ajax_must_login');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }


        $user = UserLMS::find(Auth()->id());

        // check course [parent] Subscribtion
        // check course [parent] enrolls
        // check completed lessons before this
        //check retake number if quiz or course

        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }


        $course = null;


        if ($course_id) {

            $course = Course::find($course_id);
            if (!$course) {
                $message = __('LMS::messages.something_happen');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                return response()->json(['success' => false, 'view' => $view]);
            }

        }


        if (!$course_id) {



            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz->id,
                'user'      => $user,
                'parent'    => [],

            ];
            $parent = false;

        } else {

            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz->id,
                'user'      => $user,
                'parent'    => ['type' => 'course', 'id' => $course->id],

            ];

            $parent = true;

        }


        if ($course_id) {
            //check if course subscribed

            $subscriptionStatus = \Subscriptions::check_subscription([
                'module'    => 'course',
                'module_id' => $course_id,
                'user'      => $user,
                'parent'    => []
            ]);


            if (!$subscriptionStatus['success'] && $subscriptionStatus['status'] < 1) {
                $message = __('LMS::messages.ajax_check_subscription');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                return response()->json(['success' => false, 'view' => $view]);
            }


            if ($subscriptionStatus['success'] && $subscriptionStatus['status'] > 0) {
                if ($course->pagination_lessons < 1) {
                    $response = \Logs::can_enroll_course_item($moduleArray);

                    if (!$response['success']) {
                        $message = __('LMS::messages.cannot_show_content');
                        $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                        return response()->json(['success' => false, 'view' => $view]);
                    }

                }

                // $log = \Logs::enroll($moduleArray);
            }


        } else {  //end check course


            $quiz_enroll_status = $this->quiz_enroll_status($quiz, $course, $parent);

            if (!$quiz_enroll_status['success']) {

                $message = __('LMS::messages.cannot_show_content');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                return response()->json(['success' => false, 'view' => $view]);

            }


            if ($quiz_enroll_status['enroll_status']) {

                $quiz_enroll_status = $quiz_enroll_status['enroll_status'];
                if ($quiz_enroll_status['status'] < 1) {
                    $message = __('LMS::messages.cannot_show_content');
                    $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                    return response()->json(['success' => false, 'view' => $view]);
                }

            }

        } //end enroll quiz


        //if completed quiz [or enterd quiz]


        $log = \Logs::enroll($moduleArray);

        if (!$log['success']) {
            $message = __('LMS::messages.cannot_show_content');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();
            return response()->json(['success' => false, 'view' => $view]);
        }
        $createdTime = $log['itemLog']->created_at;


        $finishTime = $createdTime->addSeconds($quiz->duration * 60);

        $remainTime = $finishTime->diffInSeconds(Carbon::now());


        $quizLogs = $log['itemLog'];
        return response()->json(['success' => true, 'url' => route('quizzes.quizPage', ['quiz' => $quiz->hashed_id, 'course' => $course_id]), 'view' => '']);

    }

    public function previewQuestion(Request $request, $quiz_hashed_id, $question_hashed_id)
    {

        //check if Quiz is privet or not
        if (!Auth::check()) {
            return redirect()->back()->with(['message' => __('LMS::messages.must_login_to_show'), 'alert_type' => 'danger']);
        }


        $quiz_id = hashids_decode($quiz_hashed_id);
        $question_id = hashids_decode($question_hashed_id);

        $quiz = Quiz::find($quiz_id);

        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }


        $question = Question::find($question_id);

        if (!$question) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $teacher = UserLMS::find($quiz->author_id);
        if (!$teacher) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }
        return view('quizzes.question_preview')->with(compact('quiz', 'question', 'teacher'))->render();


    }


    private function enrollQuestion($question_id, $quiz, $quizLogs, $showAnswer = false, $prev = false, $preview = false)
    {

        $previewQuestion = $preview;

        $view = view('partials.quiz_body.templates.questions')->with(compact('currentQuestion', 'quiz', 'prevQuestion', 'nextQuestion', 'quizLogs', 'questionLogs', 'quizQuestions', 'answeredQuestions', 'showAnswer', 'previewQuestion'))->render();

        return response()->json(['success' => true, 'view' => $view]);
    }


    public function answerQuestions(Request $request, $quiz_hash_id, $logs_hash_id)
    {


        $quiz_id = hashids_decode($quiz_hash_id);
        $quiz_logs_id = hashids_decode($logs_hash_id);

        $quiz = Quiz::find($quiz_id);

        if (empty($quiz)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $quizLogs = StudentLogs::find($quiz_logs_id);


        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

      if ($request->session()->has('questions_quiz_session_' . $quiz_hash_id)) {
            $quiz_session = $request->session()->get('questions_quiz_session_' . $quiz_hash_id);
             $quiz = $quiz_session['data'];
        } else {
            $quiz = Quiz::with('questions')->findOrFail($quiz_id);
            $request->session()->put('questions_quiz_session_' . $quiz_hash_id, ['data' => $quiz]);
        }


        $questionsIds = $request->get('questions');
        $quizQuestions = $quiz->questions()->where('question_type', '!=', 'paragraph')->get();
        if ($questionsIds) {

            foreach ($questionsIds as $question_hashed_id) {
                $question_id = hashids_decode($question_hashed_id);
                $question = $quizQuestions->where('id', $question_id)->first();

                $currentQuestionLogs = StudentLogs::where('lms_loggable_id', $question_id)->where('lms_loggable_type', 'question')->where('parent_id', $quizLogs->id)->first();

                if (empty($currentQuestionLogs)) {

                    $questionLogs = StudentLogs::create([
                        'user_id'           => user()->id,
                        'lms_loggable_type' => 'question',
                        'lms_loggable_id'   => $question_id,
                        'passed'            => false,
                        'status'            => 0,
                        'parent_id'         => $quizLogs->id

                    ]);
                }

                $userAttributes = [
                    'answers' => $request->input('answers.' . $question_hashed_id, []),
                ];

                $skipped = false;

                if ($quizLogs->status < 1) {

                    $respons = \Logs::answerQuestion($currentQuestionLogs, $question, $skipped, $userAttributes);

                    if (!$respons['success']) {
                        $message = __('LMS::messages.cannot_show_content');
                        $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                        return response()->json(['success' => false, 'view' => $view]);
                    }


                    $course = null;
                    if ($request->get('course')) {

                        $course_id = hashids_decode($request->get('course'));

                        $course = Course::find($course_id);

                        if (empty($course)) {

                            $message = __('LMS::messages.something_happen');
                            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                            return response()->json(['success' => false, 'view' => $view]);

                        }

                    }


                    $finish_quiz = $request->get('finish_quiz');
                    if ($finish_quiz) {


                        return $this->finishQuiz($quiz, $quizLogs, $course);
                    }


                }

            }

        }

        return response()->json(['success' => true, 'view' => '']);

    }


    public function answerQuestion(Request $request, $quiz_hash_id, $question_hash_id)
    {


        $quiz_id = hashids_decode($quiz_hash_id);
        $question_id = hashids_decode($question_hash_id);
        $quiz_logs_id = hashids_decode($request->get('quiz_logs'));
        // $showAnswer = $request->get('showAnswer')?:false;

    }


    public function finishQuiz($quiz, $quizLogs, $course = null)
    {

        $response = $this->getQuizResult($quiz, $quizLogs);

        if (!$response['success']) {
            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }


        $quizLogs = $response['quizLogs'];

        $view = view('partials.quiz_body.templates.quiz_result')->with(compact('quizLogs', 'quiz', 'course'))->render();

        return response()->json(['success' => true, 'view' => $view]);

    }


    public function getQuizResult($quiz, $quizLogs)
    {

        $quizQuestions = $quiz->questions()->where('question_type', '!=', 'paragraph')->where('status', '>', 0)->get();


        if (!$quizQuestions) {
            return ['success' => false, 'status' => 0, 'message' => 'error happen']; //@transM
        }

        $answeredQuestionsIds = [];

        $answeredQuestions = $quizLogs->children();

        $correctAnsweredCount = 0;

        $quizQuestionsCount = $quizQuestions->count();

        if (!empty($answeredQuestions)) {
            $answeredQuestionsIds = $answeredQuestions->where('lms_loggable_type', 'question')->pluck('lms_loggable_id')->toArray();

            $correctAnsweredCount = $answeredQuestions->where('passed', true)->count();
        }


        $answeredQuestions->update(['status' => 1]);
        // foreach ($answeredQuestions as $answeredQuestion) {
        // $answeredQuestion->update([
        //     'status' => 1, //completed
        //    ]);

        // }


        // $notEnrolledQuestions = $quizQuestions->whereNotIn('lms_questions.id', $answeredQuestionsIds);


        // if (!empty($notEnrolledQuestions)) {

        //     foreach ($notEnrolledQuestions as $value) {
        //         StudentLogs::create([
        //             'user_id'           => Auth()->id(),
        //             'lms_loggable_type' => 'question',
        //             'lms_loggable_id'   => $value->id,
        //             'passed'            => false,
        //             'status'            => 1,
        //             'skipped'           => 1,
        //             'parent_id'         => $quizLogs->id
        //         ]);
        //     }

        // }

        $percentage = ($correctAnsweredCount / $quizQuestionsCount) * 100;


        $passed = false;
        $passing_grade = $percentage;
        $quiz_degree = $answeredQuestions->where('passed', true)->sum('degree');

        if ($passing_grade > $quiz->passing_grade) {
            $passed = true;
        } else {
            $passed = false;
        }


        $quizLogs->update([
            'passed'        => $passed,
            'status'        => 1, //completed
            'degree'        => $passing_grade,
            'passing_grade' => $quiz->passing_grade,
            'points'        => $quiz_degree,
            'finished_at'   => Carbon::now()
        ]);


        // if($quizLogs->parent_id){
        // StudentLogs::where('parent_id', $quizLogs->parent_id)->update(['status' => 1]);

        //     }else{

        //  StudentLogs::where('lms_loggable_type', 'quiz')->where('lms_loggable_id', $quiz->id)->where('user_id', Auth()->id())->update(['status' => 1]);

        //     }

        return ['success' => true, 'status' => 1, 'quizLogs' => $quizLogs, 'message' => 'completed']; //@transM


    }


    public function previewQuizLogs(Request $request, $quiz_hash_id, $quizLogs_hash_id, $question_hash_id = null)
    {

        $quiz_id = hashids_decode($quiz_hash_id);
        $question_id = hashids_decode($question_hash_id);

        $log_id = hashids_decode($quizLogs_hash_id);

        $quiz = Quiz::find($quiz_id);
        $quizLogs = StudentLogs::find($log_id);

        if (empty($quizLogs) || empty($quiz)) {
            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }


        if ($quizLogs->status < 1) {

            $message = __('LMS::messages.cannot_show_content');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        return $this->enrollQuestion($question_id, $quiz, $quizLogs, true, false, true);


    }


    public function questionEasyStatus($questionLog_id, $status = 0)
    {

        $log_id = hashids_decode($questionLog_id);

        $questionLog = StudentLogs::find($log_id);

        if (empty($questionLog)) {
            return false;
        }

        $questionLog->update([
            'easy_status' => $status

        ]);

        return true;
    }


    public function quiz_enroll_status($quiz, $course = null, $retake = false, $time = false)
    {

        // 0- not subscribed
        // 1- pass retake time
        // 2- time finished
        // 3- all thing is ok

        $user = UserLMS::find(Auth()->id());

                            //check if a sub quiz
            $parentQuiz = $quiz;

        if($quiz->is_sub_quiz > 0){
            $parentQuiz = $quiz->parent()->first();
      

        }


        if (empty($course)) {
                 $parentQuizModuleArray = [
                'module'    => 'quiz',
                'module_id' => $parentQuiz->id,
                'user'      => $user

            ];

            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz->id,
                'user'      => $user,
                'parent'    => [],

            ];
            $parent = false;

        } else {
        $parentQuizModuleArray = [
                'module'    => 'quiz',
                'module_id' => $parentQuiz->id,
                'user'      => $user,
                'parent'    => ['type' => 'course', 'id' => $course->id],


            ];

            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz->id,
                'user'      => $user,
                'parent'    => ['type' => 'course', 'id' => $course->id],

            ];

            $parent = true;

        }


        $subscriptionStatus = \Subscriptions::check_subscription($parentQuizModuleArray);

        if (!$subscriptionStatus['success'] || $subscriptionStatus['status'] < 1) {

            return ['success' => false, 'status' => 0, 'enroll_status' => [], 'itemLog' => null, 'message' => __('LMS::messages.cannot_show_content')];

        }


        $enroll_status = \Logs::enroll_status($moduleArray);

        if ($enroll_status['success']) { //enrolled
            if ($retake) {

                $logsNumber = \Logs::logsNumber($moduleArray, $parent);

                if ($quiz->retake_count && $logsNumber && $logsNumber >= $quiz->retake_count) {
                    return ['success' => false, 'status' => 1, 'enroll_status' => $enroll_status, 'itemLog' => $enroll_status['itemLog'], 'message' => 'takes time finished']; //@transM
                }

            }
        }

        if ($time && !empty($enroll_status['itemLog'])) {

            $quizLogs = $enroll_status['itemLog'];

            $createdTime = $quizLogs->created_at;

            $finishTime = $createdTime->addSeconds($quiz->duration * 60);

            if ($finishTime < Carbon::now()) {

                return ['success' => false, 'status' => 2, 'enroll_status' => $enroll_status, 'itemLog' => $enroll_status['itemLog'], 'message' => 'time finished']; //@transM

            }
        }

        return ['success' => true, 'status' => 3, 'enroll_status' => $enroll_status, 'itemLog' => $enroll_status['itemLog'], 'message' => 'time finished']; //@transM

    }


    public function showCourseQuiz(Request $request, $course_hashed_id, $quiz_hashed_id)
    {
        $course_id = hashids_decode($course_hashed_id);

        $quiz_id = hashids_decode($quiz_hashed_id);
        //check if Quiz is privet or not
        if (!Auth::check()) {
            return redirect()->back()->with(['message' => __('LMS::messages.must_login_to_show'), 'alert_type' => 'danger']);
            // $message = __('LMS::messages.something_happen');
            // return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $user = UserLMS::find(Auth()->id());

        // check course [parent] Subscribtion
        // check course [parent] enrolls
        // check completed quizzes before this
        //check retake number if quiz or course

        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $questions = $quiz->questions()->where('status', 1)->where('question_type', '!=', 'paragraph')->orderBy('parent_id', 'asc')->orderBy('pivot_order', 'asc')->paginate($quiz->question_per_page ?: 1);

        $page_title = $quiz->title;


        $course = new Course;
        // $courseSections = null;

        if ($course_id) {


            $course = Course::find($course_id);


            if (empty($course)) {
                $message = __('LMS::messages.something_happen');
                return view('partials.quiz_body.templates.error')->with(compact('message'));
            }


            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz_id,
                'user'      => $user,
                'parent'    => [
                    'type' => 'course',
                    'id'   => $course_id
                ],

            ];


            // $courseSections =  $course->sections();

            //check if course subscribed

            $subscriptionStatus = \Subscriptions::check_subscription([
                'module'    => 'course',
                'module_id' => $course_id,
                'user'      => $user,
                'parent'    => []
            ]);

            $retake_number = 1;


        } else {  //end if quiz course


            $moduleArray = [
                'module'    => 'quiz',
                'module_id' => $quiz_id,
                'user'      => $user,
                'parent'    => [],

            ];


            $retake_number = $quiz->retake_count ?: 1000000;

            //check if quiz subscribed

            $subscriptionStatus = \Subscriptions::check_subscription($moduleArray);

        }

        $enroll_status = \Logs::enroll_status($moduleArray);

        // not marked as completed quiz

        if ($enroll_status['success'] && $enroll_status['status'] < 1) {

            $quizLogs = $enroll_status['itemLog'];

            if ($quiz->duration > 0) {

                $createdTime = $quizLogs->created_at;

                $finishTime = $createdTime->addSeconds($quiz->duration * 60);

                if ($finishTime > Carbon::now()) {
                    $quizTemplate = 'questions';

                    if ($request->ajax() && $request->get('page')) {
                        //store current page
                        // if ($quizLogs->status != 1) {
                        //     $quizLogs->update(['current_page' => \Request::get('page')]); //3

                        // }

                        $quizTemplate = 'questions';

                        $view = view('partials.quiz_body.templates.questions')->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course'))->render();

                        return response()->json(['success' => true, 'view' => $view]);

                    }

                } else {

                    $response = $this->getQuizResult($quiz, $quizLogs);

                    //json

                    if ($request->ajax() && $request->get('page')) {

                        //store current page
                        // if ($quizLogs->status != 1) {
                        //     $quizLogs->update(['current_page' => \Request::get('page')]); //4

                        // }

                        if (!$response['success']) {
                            $message = __('LMS::messages.something_happen');
                            return view('partials.quiz_body.templates.error')->with(compact('message'))->render();
                        } else {

                            $quizTemplate = 'quiz_result';

                            return view('partials.quiz_body.templates.quiz_result')->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course'))->render();

                        }


                    }

                    if (!$response['success']) {

                        $message = __('LMS::messages.something_happen');
                        return view('partials.quiz_body.templates.error')->with(compact('message'));

                    }

                    $quizLogs = $response['quizLogs'];

                    $quizTemplate = 'quiz_result';


                } //end check finished time
            } //end if has duration

            $quizTemplate = 'questions'; //'questions'; show results when refresh page


            if ($request->ajax() && $request->get('page')) {

                $quizTemplate = 'questions';

                $view = view('partials.quiz_body.templates.questions')->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course'))->render();

                return response()->json(['success' => true, 'view' => $view]);

            }


        } //end status not completed

        elseif ($enroll_status['success'] && $enroll_status['status'] > 0) {


            $quizLogs = $enroll_status['itemLog'];
            $quizTemplate = 'quiz_result';

        } else {

            $quizTemplate = 'start_quiz';

        }

        //json

        if ($request->ajax() && ($request->get('show_answers') || $request->get('page'))) {
            $quizTemplate = 'questions';
            if ($quizLogs->status == 1) {
                $showAnswer = true;
                if ($request->get('page')) {
                    $temp = 'templates.questions';
                } else {
                    $temp = 'index';
                }
                $view = view('partials.quiz_body.' . $temp)->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course', 'showAnswer'))->render();

            } else {

                $showAnswer = false;
                $view = view('partials.quiz_body.templates.questions')->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course', 'showAnswer'))->render();

            }


            return response()->json(['success' => true, 'view' => $view]);

        }


        if ($course_id) {

            return view('courses.quiz')->with(compact('quizLogs', 'quiz', 'course', 'quizTemplate', 'page_title', 'questions', 'subscriptionStatus'));

        }

        return view('quizzes.questions')->with(compact('quizLogs', 'quiz', 'course', 'quizTemplate', 'page_title', 'questions', 'subscriptionStatus'));


    }


    public function retakeQuiz(Request $request, $quiz_hashed_id)
    {

        if (!Auth::check()) {
            $message = __('LMS::messages.must_login_to_show');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $user = UserLMS::find(Auth()->id());

        $quiz_id = hashids_decode($quiz_hashed_id);

        $user = UserLMS::find(Auth()->id());
        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        if ($quiz->retake_count == 1) {


            $message = __('LMS::messages.cannot_retake_quiz');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        //check if quiz subscribed

        $subscriptionStatus = \Subscriptions::check_subscription([
            'module'    => 'quiz',
            'module_id' => $quiz->id,
            'user'      => $user,
            'parent'    => []
        ]);


        if (!$subscriptionStatus['success'] && $subscriptionStatus['status'] < 1) {

            $message = __('LMS::messages.cannot_show_page');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $response = \Logs::enroll_status([
            'module'    => 'quiz',
            'module_id' => $quiz->id,
            'user'      => $user,
            'parent'    => []
        ]);

        if (!$request->get('create_new_logs')) {

            if ($response['status'] != 1) {
                $message = __('LMS::messages.cannot_retake_quiz');
                return view('partials.quiz_body.templates.error')->with(compact('message'));


            }

        }


        $quizLogs = $response['itemLog'];
        if (empty($quizLogs)) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        if ($quizLogs->parent_id) {

            $message = __('LMS::messages.cannot_retake_quiz');
            return view('partials.quiz_body.templates.error')->with(compact('message'));

        }

        $counLogsTimes = $quizLogs->where('parent_id', null)->count();


        if ($quiz->retake_count && $counLogsTimes >= $quiz->retake_count) {
            $message = __('LMS::messages.cannot_retake_quiz');
            return view('partials.quiz_body.templates.error')->with(compact('message'));;
        }

        if ($request->get('create_new_logs')) {

            $response = $this->getQuizResult($quiz, $quizLogs);

        }


        StudentLogs::Create([
            'user_id'           => $user->id,
            'lms_loggable_type' => 'quiz',
            'lms_loggable_id'   => $quiz->id,

        ]);


        return redirect()->route('quizzes.quizPage', $quiz->hashed_id);

    }

    public function delayedQuestions(Request $request, $quiz_hashed_id, $logs_hashed_id, $course_hashed_id = null)
    {


        $quiz_id = hashids_decode($quiz_hashed_id);
        $logs_id = hashids_decode($logs_hashed_id);
        $course_id = hashids_decode($course_hashed_id);
        //check if Quiz is privet or not
        if (!Auth::check()) {
            $message = __('LMS::messages.must_login_to_show');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $user = UserLMS::find(Auth()->id());

        // check course [parent] Subscribtion
        // check course [parent] enrolls
        // check completed quizzes before this
        //check retake number if quiz or course

        if ($request->segment(1) == 'courses') {
            $courseTemp = true;
        } else {
            $courseTemp = false;
        }

        $quiz = Quiz::find($quiz_id);
        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $quizLogs = StudentLogs::where('user_id', Auth()->id())->where('id', $logs_id)->first();
        if (!$quizLogs) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $course = New Course;

        if ($course_id) {
            $course = Course::find($course_id);
            if (!$course) {
                $message = __('LMS::messages.something_happen');
                return view('partials.quiz_body.templates.error')->with(compact('message'));
            }
        }
        $subscriptionStatus = ['success' => true, 'status' => 1, 'message' => 'not subscribed'];;

        $delayedQuestionsIds = $quizLogs->children()->where('lms_loggable_type', 'question')->where('delayed', 1)->pluck('lms_loggable_id')->toArray();

            $questions = $quiz->questions()->where('question_type', '!=', 'paragraph')->whereIn('lms_questions.id', $delayedQuestionsIds)->orderBy('parent_id', 'asc')->orderBy('pivot_order', 'asc')->paginate($quiz->question_per_page ?: 1);
             $questionsList = $quiz->questions()->where('question_type', '!=', 'paragraph')->whereIn('lms_questions.id', $delayedQuestionsIds)->orderBy('parent_id', 'asc')->orderBy('pivot_order', 'asc')->select('lms_questions.content','lms_questions.id','lms_questions.parent_id')->get()->pluck('content','id')->toArray();



        $page_title = $quiz->title;

        if ($quizLogs->status != 1) {
            $showAnswer = false;
        } else {
            $showAnswer = true;
        }

        $quizTemplate = 'questions';

        $delayed = true;

        //json


        if ($request->ajax() && $request->get('page')) {

            if ($request->get('page')) {
                $temp = 'templates.questions';
            } else {
                $temp = 'index';
            }
            $view = view('partials.quiz_body.' . $temp)->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course', 'showAnswer', 'delayed', 'subscriptionStatus', 'questionsList'))->render();

            return response()->json(['success' => true, 'view' => $view]);

        }
        if ($courseTemp) {
            $template = 'courses.quiz';
        } else {
            $template = 'quizzes.questions';
        }

        return view($template)->with(compact('quizLogs', 'quiz', 'course', 'courseSections', 'quizTemplate', 'page_title', 'questions', 'delayed', 'showAnswer', 'subscriptionStatus', 'questionsList'));
    }


    public function loadEmbededQuiz(Request $request, $quiz_hashed_id, $course_hashed_id = null)
    {

        $ajax = $request->get('ajax');
        $ifram_url = route('quizzes.quizPage', ['quiz_id' => $quiz_hashed_id, 'course_id' => $course_hashed_id]);

        return view('partials.quiz_body.embeded')->with(compact('ifram_url', 'ajax'));


    }


    public function check_answers(Request $request, $quiz_hashed_id, $quiz_logs_hashed_id, $question_hashed_id)
    {


        $quiz_logs_id = hashids_decode($quiz_logs_hashed_id);
        $question_id = hashids_decode($question_hashed_id);

        $quiz_id = hashids_decode($quiz_hashed_id);

        if (!Auth::check()) {
            $message = __('LMS::messages.ajax_must_login');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);
        }

        $quiz = Quiz::find($quiz_id);

        if (empty($quiz)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $quizLogs = StudentLogs::find($quiz_logs_id);

        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $question = Question::find($question_id);


        if (empty($question)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }


        $currentQuestionLogs = StudentLogs::where('lms_loggable_id', $question_id)->where('lms_loggable_type', 'question')->where('parent_id', $quizLogs->id)->first();

        if (empty($currentQuestionLogs)) {

           $currentQuestionLogs = StudentLogs::create([
                'user_id'           => user()->id,
                'lms_loggable_type' => 'question',
                'lms_loggable_id'   => $question_id,
                'passed'            => false,
                'status'            => 0,
                'parent_id'         => $quizLogs->id

            ]);
        }


        $userAttributes = [
            'answers' => $request->input('answers.' . $question_hashed_id, []),
        ];

        $skipped = true;


        if ($quizLogs->status < 1 && $currentQuestionLogs->preview < 1) {
            $respons = \Logs::answerQuestion($currentQuestionLogs, $question, $skipped, $userAttributes, true);

            if (!$respons['success']) {
                $message = __('LMS::messages.cannot_show_content');
                $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

                return response()->json(['success' => false, 'view' => $view]);
            }

        }

        $showAnswer = true;
        $view = view('partials.quiz_body.templates.question')->with(compact('showAnswer', 'question', 'quiz', 'quizLogs'))->render();
        return response()->json(['success' => true, 'view' => $view]);

    }


    public function markAsDelayed(Request $request, $quiz_hashed_id, $quiz_logs_hashed_id, $question_hashed_id)
    {


        $quiz_logs_id = hashids_decode($quiz_logs_hashed_id);
        $question_id = hashids_decode($question_hashed_id);
        $quiz_id = hashids_decode($quiz_hashed_id);

        $quiz = Quiz::find($quiz_id);

        if (empty($quiz)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view, 'is_delayed' => 0]);

        }

        $quizLogs = StudentLogs::find($quiz_logs_id);

        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view, 'is_delayed' => 0]);

        }

        $question = Question::find($question_id);


        if (empty($question)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view, 'is_delayed' => 0]);

        }


        //check if question have logs


        $questionLogs = $quizLogs->children()->where('lms_loggable_id', $question_id)->where('lms_loggable_type', 'question')->first();

        if (!$questionLogs) {

            StudentLogs::create([
                'user_id'           => user()->id,
                'lms_loggable_type' => 'question',
                'lms_loggable_id'   => $question_id,
                'passed'            => false,
                'status'            => 0,
                'parent_id'         => $quizLogs->id,
                'delayed'           => 1
            ]);

            $is_delayed = 1;
        } else {
            if ($questionLogs->delayed) {
                $is_delayed = 0;
                $questionLogs->update(['delayed' => 0]);
            } else {
                $is_delayed = 1;
                $questionLogs->update(['delayed' => 1]);
            }

        }

        $view = view('partials.quiz_body.delayed')->with(compact('is_delayed', 'quiz', 'quizLogs', 'question', 'questionLogs'))->render();


        return response()->json(['success' => true, 'view' => $view, 'is_delayed' => $is_delayed]);


    }


    public function favourite_questions(Request $request, $quiz_hashed_id, $logs_hashed_id, $course_hashed_id = null)
    {

        $quiz_id = hashids_decode($quiz_hashed_id);
        $logs_id = hashids_decode($logs_hashed_id);
        $course_id = hashids_decode($course_hashed_id);
        $dont_delayed_remove_row = true;
        //check if Quiz is privet or not
        if (!Auth::check()) {
            $message = __('LMS::messages.ajax_must_login');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $user = UserLMS::find(Auth()->id());

        // check course [parent] Subscribtion
        // check course [parent] enrolls
        // check completed quizzes before this
        //check retake number if quiz or course

        if ($request->segment(1) == 'courses') {
            $courseTemp = true;
        } else {
            $courseTemp = false;
        }


         $quiz = Quiz::findOrFail($quiz_id);


             if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }



        $quizLogs = StudentLogs::where('user_id', Auth()->id())->where('id', $logs_id)->first();
        if (!$quizLogs) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $course = New Course;

        if ($course_id) {
            $course = Course::find($course_id);
            if (!$course) {
                $message = __('LMS::messages.something_happen');
                return view('partials.quiz_body.templates.error')->with(compact('message'));
            }
        }


        $subscriptionStatus = ['success' => true, 'status' => 1, 'message' => 'not subscribed'];;

        $delayedQuestionsIds = $quizLogs->children()->where('lms_loggable_type', 'question')->where('delayed', 1)->pluck('lms_loggable_id')->toArray();


        $favouriteQuestionsIds = Favourite::where('user_id', Auth()->id())->where('favourittable_type', 'question')->pluck('favourittable_id')->toArray();


            $questions = $quiz->questions()
            ->where('status', 1)
            ->whereIn('lms_questions.id', $favouriteQuestionsIds)
            ->where('question_type', '!=', 'paragraph')
            ->orderBy('parent_id', 'asc')
            ->orderBy('pivot_order', 'asc')->paginate($quiz->question_per_page ?: 1);
             $questionsList = $quiz->questions()
            ->where('status', 1)
            ->whereIn('lms_questions.id', $favouriteQuestionsIds)
            ->where('question_type', '!=', 'paragraph')
            ->orderBy('parent_id', 'asc')->
            orderBy('pivot_order', 'asc')->select('lms_questions.content','lms_questions.id','lms_questions.parent_id')->get()->pluck('content','id')->toArray();



        $page_title = $quiz->title;

        if ($quizLogs->status != 1) {
            $showAnswer = false;
        } else {
            $showAnswer = true;
        }

        $quizTemplate = 'questions';

        $delayed = true;

        //json

        if ($request->ajax() && $request->get('page')) {

            if ($request->get('page')) {
                $temp = 'templates.questions';
            } else {
                $temp = 'index';
            }
            $view = view('partials.quiz_body.' . $temp)->with(compact('quizLogs', 'quiz', 'questions', 'quizTemplate', 'course', 'showAnswer', 'delayed', 'subscriptionStatus', 'questionsList','dont_delayed_remove_row'))->render();

            return response()->json(['success' => true, 'view' => $view]);

        }
        if ($courseTemp) {
            $template = 'courses.quiz';
        } else {
            $template = 'quizzes.questions';
        }

        $q_delete_row = true;

        return view($template)->with(compact('quizLogs', 'quiz', 'course', 'courseSections', 'quizTemplate', 'page_title', 'questions', 'delayed', 'showAnswer', 'subscriptionStatus', 'questionsList','q_delete_row','dont_delayed_remove_row'));


    }


    public function getAskTeacherModal(Request $request, $quiz_hashed_id, $question_hashed_id)
    {

        //check if Quiz is privet or not
        if (!Auth::check()) {
            $message = __('LMS::messages.must_login_to_show');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }


        $quiz_id = hashids_decode($quiz_hashed_id);
        $question_id = hashids_decode($question_hashed_id);

        $quiz = Quiz::find($quiz_id);

        if (!$quiz) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }


        $question = Question::find($question_id);

        if (!$question) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }

        $teacher = UserLMS::find($quiz->author_id);
        if (!$teacher) {
            $message = __('LMS::messages.something_happen');
            return view('partials.quiz_body.templates.error')->with(compact('message'));
        }
        return view('partials.quiz_body.components.ask_teacher_form')->with(compact('quiz', 'question', 'teacher'))->render();


    }

    public function ajax_get_quiz_progress(Request $request, $quiz_hashed_id, $logs_hashed_id)
    {


        $quiz_id = hashids_decode($quiz_hashed_id);
        $logs_id = hashids_decode($logs_hashed_id);

        $quiz = Quiz::find($quiz_id);

        if (empty($quiz)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $quizLogs = StudentLogs::find($logs_id);


        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        $hide_btns = false;
        if($request->get('hide_btns') == true){
            $hide_btns = true;
        }

        $view = view('partials.quiz_body.components.progress_results')->with(compact('quizLogs', 'quiz', 'hide_btns'))->render();
  
        return response()->json(['success' => true, 'view' => $view]);


    }

    public function stop_here_page(Request $request, $logs_hashed_id, $page)
    {

        $logs_id = hashids_decode($logs_hashed_id);

        $quizLogs = StudentLogs::find($logs_id);
        if (empty($quizLogs)) {

            $message = __('LMS::messages.something_happen');
            $view = view('partials.quiz_body.templates.error')->with(compact('message'))->render();

            return response()->json(['success' => false, 'view' => $view]);

        }

        if ($page < 1 || $request->get('status') == 'remove') {
            $page = null;
        }

        $quizLogs->update(['current_page' => $page]);

        return response()->json(['success' => true, 'view' => '']);


    }

        public function load_board(Request $request)
    {
       $hashed_id = $request->get('question');

               $logs_id = hashids_decode($hashed_id);
               $board_data = null;

        $questionLogs = StudentLogs::find($logs_id);

    if ($request->session()->has('questions_board_session_' . $request->get('question'))) {

            $board_session = $request->session()->get('questions_board_session_' . $request->get('question'));
            if($board_session['data'] !== 'null'){
             $board_data = $board_session['data'];
            }

           }else{

        if($questionLogs && $questionLogs->white_board){

    
            $board_data = $questionLogs->white_board;
        }

           }



        return view('partials.quiz_body.components.board.embeded_board')->with(compact('hashed_id', 'questionLogs', 'board_data'));


    }

public function save_board_in_session(Request $request)
    {

        $request->get('question');
        if($question){

     if ($request->session()->has('questions_board_session_' . $question)) {

            $board_session = $request->session()->get('questions_board_session_' . $question);
             $data = $board_session['data'];
        } else {
                        session()->forget('questions_board_session_' . $request->get('question'));

            $request->session()->put('questions_board_session_' . $question, ['data' => json_encode($request->board_data)]);
        }

        }
        return $question;


    }


    public function save_board_in_database(Request $request)
    {

        $logs_id = hashids_decode($request->get('question'));

        if($request->get('save_type') > 0){

        $questionLogs = StudentLogs::find($logs_id);
        if (empty($questionLogs)) {


          return   response()->json(['success' => false, 'src' => '']);

        }


        $questionLogs->update(['white_board' => json_encode($request->get('white_board'))]);

         return response()->json(['success' => true, 'src' => $request->get('white_board')]);

        }else{
            session()->forget('questions_board_session_' . $request->get('question'));

            $request->session()->put('questions_board_session_' . $request->get('question'), ['data' => json_encode($request->white_board)]);
  

        return response()->json(['success' => true, 'src' => $request->get('white_board')]);


        }




    
    }

        public function get_embedded_video(Request $request)
    {

                $embeded = $request->get('preview');

            return view('components.embeded_media')->with(compact('embeded'))->render();

}

public function handelQuizRequest($parent_name = null, $parent_hashed_id = null, $child_name = null, $child_hashed_id = null)
    {
        $plan = null;
        $course = null;

            if($parent_name == 'packages'){
               $parent_id = hashids_decode($parent_hashed_id);
                $plan = Plan::find($parent_id);
                if(!$plan){
                    return ['success' => false];
                }
               if($child_name == 'courses'){
                $child_id = hashids_decode($child_hashed_id);
                $course = Course::find($child_id);
             if(!$course){
                    return ['success' => false];
                }
               } 
            }

           if($parent_name == 'courses'){

                $parent_id = hashids_decode($parent_hashed_id);
                $course = Course::find($parent_id);
                if(!$course){
                    return ['success' => false];
                }

           } 

               

            return ['success' => true, 'course' => $course, 'plan' => $plan];

}


   


}
