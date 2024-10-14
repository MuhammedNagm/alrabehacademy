
@php
    $current_page_id = \Request::get('page')?:1;
    $currentQuestion = [];
     $delayed_questions =  $quizLogs->children()->where('lms_loggable_type', 'question')->where('delayed', 1)->get();

        $is_delayed = false;

            if(isset($delayed)){
              $is_delayed = $delayed;
            }
            if(\Request::segment(3) == 'delayed'){
                 $is_delayed = true;
            }

             $showAnswer = isset($showAnswer)?$showAnswer:false;

            if(\Request::segment(1) == 'courses'){
                $is_course_element = true;
            }else{
               $is_course_element = false;
            }


@endphp

@php
    $questionsIdsArr = [];
    if (is_array($questionsList)){
    $questionsIdsArr = array_keys($questionsList);
    }

    $countFav = Modules\Components\LMS\Models\Favourite::where('user_id', Auth()->id())->where('favourittable_type', 'question')->whereIn('favourittable_id', $questionsIdsArr)->count();
@endphp

@php
    $subQuizLogs = null;
          $sub_quiz = $quiz->children()->first();
          $main_quiz = $quiz->parent()->first();


    if($sub_quiz){

        if($course){
           $moduleArray = [
                    'module'    => 'quiz',
                    'module_id' => $sub_quiz->id,
                    'user'      => user(),
                    'parent'    => [
                        'type' => 'course',
                        'id'   => $course->id
                    ],

                ];

    }else{
            $moduleArray = [
                    'module'    => 'quiz',
                    'module_id' => $sub_quiz->id,
                    'user'      => user(),

                ];

    }

    $enroll_status = \Logs::enroll_status($moduleArray);
            if ($enroll_status['success'] && $enroll_status['status'] < 1) {

                $subQuizLogs = $enroll_status['itemLog'];
                }

    }
@endphp
@php
    $display_warning_time = false;
    if($delayed_questions->count()){
    if($quiz->duration > 0 && \LMS::getRemainTime($quiz->duration, $quizLogs->created_at) < ($quiz->duration * (1/3))){
    $display_warning_time = true;
    }
    }
@endphp
@php
    $newActionPage = $questions->currentPage();
        if($questions->lastPage() == $newActionPage && $newActionPage > 1){
            $newActionPage -= 1;

            }

            //$quizLogsNum = \Logs::logsNumber([
             //   'module'    => 'quiz',
             //   'module_id' => $quiz->id,
             //   'user'      => \Modules\Components\LMS\Models\UserLMS::find(user()->id),
             //   'parent'    => []
           // ], false);
@endphp
@php
    $hint_videos =  $quiz->hint_videos;

@endphp
@if(user()->can('LMS::quiz.edit'))
    <div class="text-right">
        <a href="{{url('lms/quizzes/'.$quiz->hashed_id.'/edit')}}" class="btn btn-warning btn-sm" type="button" target="_blank">
            <i class="fa fa-wrench"></i> تعديل الاختبار
        </a>
    </div>
@endif
<div id="show_msg_warning_time" class="row" @if(!$display_warning_time) style="display: none;" @endif>
    <div class="col-md-9 alert alert-danger">
        <strong>تنبيه!</strong> وقت الاختبار على وشك الانتهاء,ولديك اسئلة مؤجلة ... <a href="{{route('quizzes.delayed_questions', ['quiz' => $quiz->hashed_id, 'quiz_logs' => $quizLogs->hashed_id])}}" class="btn btn-sm btn-warning"> عرض  الاسئلة المؤجلة</a>
    </div>

</div>

<div class="row">
    <div class="col-md-9"> {{-- question big container --}}

        <div class="quiz_tools">

            <div class="text-left">

                <div class="btn-group">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-list-ul"></i> خيارات
                    </button>
                    <button class="btn btn-danger btn-sm show-hide-board close-board-btn" type="button" style="display: none;"><i class="fa fa-close"></i></button>

                    <div class="dropdown-menu dropdown-menu-right" style="width: 253px;">


                        @if($quiz->is_sub_quiz > 0)

                            <a class="dropdown-item" href="{{route('quizzes.quizPage', ['quiz' => $main_quiz?$main_quiz->hashed_id:$quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}"><i class="fa fa-book"></i> فاهم /اختبر نفسك</a>
                        @else
                            @if($sub_quiz)
                                <a class="dropdown-item" href="{{route('quizzes.quizPage', ['quiz' => $sub_quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}" ><i class="fa fa-pencil"></i> اختبر نفسك</a>
                            @endif
                        @endif


                        @if($quiz->duration > 0)
                            @if($quizLogs->status < 1 && !$is_delayed)
                                <a class="dropdown-item" data-count="{{$delayed_questions->count()}}" id="delayeds-btn-questions"   href="{{route('quizzes.delayed_questions', ['quiz' => $quiz->hashed_id, 'quiz_logs' => $quizLogs->hashed_id])}}" ><i class="fa fa-eye"></i> عرض الاسئلة المؤجلة  ({{$delayed_questions->count()}})</a>
                            @endif
                            @if($quizLogs->status < 1 && $is_delayed)
                                <a class="dropdown-item" data-count="{{$countFav}}" id="delayeds-btn-questions"   href="{{route('quizzes.quizPage', ['quiz' => $quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}" ><i class="fa fa-list"></i> عرض الاسئلة الغير مؤجلة</a>

                            @endif
                        @endif
                        @if($quiz->duration < 1)

                            <a class="dropdown-item" data-count="{{$countFav}}" id="favs-btn-questions"   href="{{route('quizzes.favourite_questions', ['quiz_id' => $quiz->hashed_id, 'logs_id' => $quizLogs->hashed_id, 'course' => $course?$course->hashed_id:''])}}" @if($course) target="_blank" @endif><i class="fa fa-heart"></i> عرض المفضلة  ({{$countFav}})</a>
                        @endif

                        <a href="javascript:;" data-question="10" id="show_board_10" class="show-hide-board dropdown-item"><i class="fa fa-pencil-square-o" ></i> المسودة</a>
                        @if($hint_videos->count())

                            <a href="javascript:;" class="dropdown-item mdlFire"  data-target="#modal-1"><i class="fa fa-desktop" ></i> شروحات</a>
                        @endif

                        <a href="{{route('quizzes.quizPage', ['quiz' => $quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}?page={{$quizLogs->current_page?:1}}"  class="dropdown-item"><i class="fa fa-play-circle"></i> الإستكمال من حيث توقفت  </a>
                        <a class="dropdown-item" href="javascript:;">
                            @php
                                $course_req = $course?'?course='.$course->hashed_id:'';
                            @endphp
                            {!! Form::model($quiz, ['url' => route('quizzes.retakeQuiz', ['quiz_id' => $quiz->hashed_id]).$course_req,'method'=>'POST','files'=>true]) !!}
                            <input type="hidden" name="create_new_logs" value="1">
                            <input type="hidden" name="quiz_retake" value="1">
                            <button type="submit" id="retake_quiz_btn" style="border: none; padding: 0; background: none;"><i class="fa fa-reply"></i> إعادة  الاختبار مرة اخرى </button>
                            {{--<br> @if($quizLogsNum > 1 || $quizLogsNum < 11)
                            <small style="text-align: center;">[تم إعادة  الاختبار  {{$quizLogsNum}} مرات   ]</small> @else<small style="text-align: center;">[تم إعادة  الاختبار  {{$quizLogsNum}} مرة  ]</small>  @endif--}}
                            {!! Form::close() !!}
                        </a>

                        {{--      					   @if($quizLogs->status == 1 || $quiz->duration < 1)
                            <a href="javascript:;" class="dropdown-item showResultsForQuizBtn" data-url="{{route('quizzes.ajax_get_quiz_progress', ['quiz_id' => $quiz->hashed_id, 'log_id' => $quizLogs->hashed_id])}}"><i class="fa fa-area-chart"></i> تحليل إجاباتك </a>
                             @endif --}}
                        <div class="dropdown-divider"></div>

                        @if($quizLogs->status != 1)

                            <a href="#finishQuizModal" data-toggle="modal" data-target="#finishQuizModal"  class="dropdown-item">

                                <i class="fa fa-sticky-note"></i>  @lang('developnet-lms::labels.spans.span_finish_exam')
                            </a>
                        @endif

                    </div>

                </div>
                @if(!$is_course_element)




                @endif

                <div class="btn_close_video_container btn-group" style="display: none;">
                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-desktop"></i>
                    </button><button type="button" class="show-quiz-video btn_close_video_container" style="display: none;"><i class="fa fa-times-circle"></i></button>

                    <div class="dropdown-menu dropdown-menu-right" style="width: 253px;">
                        @foreach($hint_videos as $row)

                            <a class="show-embeded-video {{($loop->index < 1)?'active-video-item':''}}  dropdown-item" href="javascript:;" data-url="{{$row->file_url}}">{{$row->title}}</a>
                        @endforeach

                    </div>
                </div>


            </div>

            @php
                $ifram_url = url('ajax/load-board');
            @endphp
            @include('partials.quiz_body.components.board.show_board')

            <div id="showResultsForQuiz" style="display: none;">
                لا توجد بيانات لعرضها.
            </div>
        </div>

        <div id="quiz_duration" data-quiz_duration="{{\LMS::getRemainTime($quiz->duration, $quizLogs->created_at)}}"></div>
        <input type="hidden" value="30%" id="question_progress" data-title="30% Complete (success)">

        {!! Form::model($quiz, ['url' => route('quizzes.answer_questions', ['quiz_id' => $quiz->hashed_id, 'logs_id' => $quizLogs->hashed_id]),'method'=>'POST','files'=>true, 'class' => 'ajax_questions_form', 'id' => 'main-questions-form']) !!}

        <input type="hidden" id="result_template" value=0>
        @if($questions->count())

            @foreach($questions as $question)



                {{-- push questions --}}

                <input id="last_page_id" type="hidden" value="{{$questions->lastPage()}}">


                @php
                    $currentQuestion[] = $question->id;

                     $questionLogs = $quizLogs->children()->where('lms_loggable_type', 'question')->where('lms_loggable_id', $question->id)->first();

                         if(empty($questionLogs)){
                                $questionLogs = \Modules\Components\LMS\Models\Logs::create([
                                'user_id' => user()->id,
                                'lms_loggable_type' => 'question',
                                'lms_loggable_id' => $question->id,
                                'passed' => false,
                                'status' => 0,
                                'parent_id' => $quizLogs->id

                            ]);

                            }

                            $ifram_url = url('ajax/load-board').'?question='.$questionLogs->hashed_id;



                       // $previewQuestion = isset($previewQuestion)?$previewQuestion:false;


                    $correctAnswersIds = $question->answers()->where('is_correct', 1)->pluck('lms_answers.id')->toArray();
                    // dd($correctAnswersIds);
                    $correctAnswersArray = [];
                    $userAnswers = [];
                    $userWrongAnswers = [];
                    $userCorrectAnswers = [];

                    $is_answered = false;

                    foreach ($correctAnswersIds as $key => $value) {
                        $correctAnswersArray[] = hashids_encode($value);
                    }

                        if(!empty($questionLogs->options)){
                            $optionsArray = json_decode($questionLogs->options, true);
                            $userAnswers = $optionsArray['answers'];
                            if(!empty($userAnswers)){
                                $is_answered = true;
                                foreach($userAnswers as $answerRow){
                                    if(!in_array($answerRow, $correctAnswersArray)){
                                     $userWrongAnswers[] = $answerRow;

                                    }else{
                                        $userCorrectAnswers[] = $answerRow;
                                    }
                                }
                            }
                        }
                        $checkAnswer = $questionLogs->preview;




                    // 	dd($correctAnswersArray,
                    // $userWrongAnswers,
                    // $userCorrectAnswers);

                         if($checkAnswer > 0){
                          $showAnswer = true;

                        }

                        if(!$showAnswer){
                    $correctAnswersArray = [];
                    $userWrongAnswers = [];
                    $userCorrectAnswers = [];
                        }



                @endphp
                @if(user()->can('LMS::quiz.edit'))
                    <div class="text-right">
                        <a href="{{url('lms/quizzes/'.$quiz->hashed_id.'/questions/'.$question->hashed_id.'/edit')}}" class="btn btn-warning btn-sm" type="button" target="_blank">
                            <i class="fa fa-wrench"></i> تعديل السؤال
                        </a>
                    </div>
                @endif
                <div class="quiz-questions" id="question_{{$question->hashed_id}}">


                    <input type="hidden" name="questions[]" value="{{$question->hashed_id}}">
                    <div class="card" style="border-top: 3px solid #02475f; margin-bottom: 20px " id="question-card-{{$question->hashed_id}}">
                        <div class="card-body">



                            @if(!$is_course_element)
                                @if($quiz->duration < 1)
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="pull-right">


                                                @include('components.q_favourite_action', ['module' => 'question', 'module_hash_id' => $question->hashed_id, 'favs_btn_id' => 'favs-btn-questions','q_url' => \Request::url().'?page='.$newActionPage])

                                            </div>

                                            @if(!$is_delayed  && $quizLogs->status < 1)

                                                <div class="pull-left">

                                                    <button  type="button" class="stop_here_btn @if($current_page_id == $quizLogs->current_page ) btn btn-danger btn-sm active @else btn btn-primary btn-sm @endif " data-page='{{$current_page_id}}' data-quiz_logs="{{$quizLogs->hashed_id}}" title="توقف هنا" ><i class="fa fa-stop-circle"></i> توقف هنا </button>
                                                </div>
                                            @endif


                                        </div>
                                    </div>
                                @endif
                            @endif
                            <br>
                            <div class="q-redius-box">
                                <div class="question-name">

                                    @include('partials.quiz_body.paragraph', ['question' => $question])

                                    @if($question->show_question_title)
                                        <h3>{{$question->title}}</h3>
                                        <hr>
                                    @endif

                                    @if($question->preview_video)

                                        @include('components.embeded_media', ['embeded' => $question->preview_video])
                                    @endif



                                </div>
                                <div class="question-content table-responsive" style="line-height: 1.8; font-size: 17px; font-weight: bold; display:inline-block; overflow-x: auto;">
                                    @if($quiz->numbers_in_arabic) {!! convertToArabicNumbers($question->content) !!} @else {!! $question->content !!} @endif
                                    <br>
                                </div>
                            </div>

                            <br>
                            @php

                                if($questionLogs->preview > 0){
                                    $answer_mouse_class = 'not-allowed';
                                }else{
                                    $answer_mouse_class = 'mouse-pointer';
                                }

                            @endphp

                            @foreach($question->answers()->get() as $answer)
                                @if($question->question_type == 'multi_choice')
                                    <div class="form-group checkbox ck-button answer-row" data-question="{{$question->hashed_id}}">
                                        <label>
                                            @if($questionLogs->preview)
                                                <input type="hidden" name="answers[{{$question->hashed_id}}][]" value="{{$answer->hashed_id}}">
                                                <input type="checkbox" disabled="" @if(in_array($answer->hashed_id, $userAnswers)) class="form-control" checked="" @endif hidden  > <span class="main_span {{$answer_mouse_class}}" @if(in_array($answer->hashed_id, $userWrongAnswers)) style="background-color: red; color: #fff;" @elseif(in_array($answer->hashed_id, $correctAnswersArray)) style="background-color: #28a745; color: #fff;"@endif> @if($quiz->numbers_in_arabic) {!! convertToArabicNumbers($answer->title) !!} @else {!! $answer->title !!} @endif</span>
                                            @else
                                                <input type="checkbox" name="answers[{{$question->hashed_id}}][]" value="{{$answer->hashed_id}}" @if(in_array($answer->hashed_id, $userAnswers)) class="form-control" checked="" @endif hidden  > <span class="main_span {{$answer_mouse_class}}" @if(in_array($answer->hashed_id, $userWrongAnswers)) style="background-color: red; color: #fff;" @elseif(in_array($answer->hashed_id, $correctAnswersArray)) style="background-color: #28a745; color: #fff;"@endif> @if($quiz->numbers_in_arabic) {!! convertToArabicNumbers($answer->title)  !!} @else {!! $answer->title !!} @endif</span>
                                            @endif
                                        </label>
                                    </div>
                                @else
                                    <div class="form-group radio ck-button answer-row" data-question="{{$question->hashed_id}}">
                                        <label>
                                            @if($questionLogs->preview)
                                                <input type="hidden" name="answers[{{$question->hashed_id}}][]" value="{{$answer->hashed_id}}">
                                                <input type="radio" disabled="" @if(in_array($answer->hashed_id, $userAnswers)) class="form-control" checked="" @endif hidden  > <span class="main_span {{$answer_mouse_class}}" @if(in_array($answer->hashed_id, $userWrongAnswers)) style="background-color: red; color: #fff;" @elseif(in_array($answer->hashed_id, $correctAnswersArray)) style="background-color: #28a745; color: #fff;"@endif> @if($quiz->numbers_in_arabic) {!! convertToArabicNumbers($answer->title)  !!} @else {!! $answer->title !!} @endif</span>
                                            @else
                                                <input type="radio" name="answers[{{$question->hashed_id}}][]" value="{{$answer->hashed_id}}" @if(in_array($answer->hashed_id, $userAnswers)) class="form-control" checked="" @endif hidden  > <span class="main_span {{$answer_mouse_class}}" @if(in_array($answer->hashed_id, $userWrongAnswers)) style="background-color: red; color: #fff;" @elseif(in_array($answer->hashed_id, $correctAnswersArray)) style="background-color: #28a745; color: #fff;"@endif> @if($quiz->numbers_in_arabic) {!! convertToArabicNumbers($answer->title)  !!} @else {!! $answer->title !!} @endif</span>
                                            @endif
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                            <br>
                            <div style="display: flex;">

                                @if($quiz->duration < 1)
                                    @if(!$quizLogs->status && $quiz->show_check_answer)
                                        <button data-checked="{{$questionLogs->preview}}" type="button" class="btn btn-danger check_answer check-ans-btn" data-question_id="{{$question->hashed_id}}" data-question_url="{{route('quizzes.check_answers', ['quiz_id' => $quiz->hashed_id, 'log_id' => $quizLogs->hashed_id, 'question_id' => $question->hashed_id])}}"  @if($questionLogs->preview > 0) disabled="" @endif > تحقق من الإجابة  </button> &nbsp;

                                    @endif
                                @endif

                                @if($quizLogs->status == 1)

                                    @if($showAnswer)
                                        @if($is_answered)
                                            @if($questionLogs->passed) <span style="color: #2bc04d;"> إجاباتك صحيحة  </span> @else <span style="color: #f10b21"> إجاباتك  خاطئة   </span> @endif

                                        @else

                                            <span style="color:#007bff">لم تقم بالإجابة على هذا السؤال.</span>

                                        @endif
                                    @endif

                                @else {{-- not completed quiz --}}

                                @if($questionLogs->preview_num > 0)
                                    @if($is_answered)

                                        @if($questionLogs->passed) <span style="color: #2bc04d;"> جوابك صحيح</span>

                                        @elseif(!$questionLogs->passed && $questionLogs->preview)

                                            <span style="color: #f10b21"> جوابك خطأ</span>

                                        @else

                                            <span style="color: #f10b21" class="check-ans-msg"> جوابك خطأ ... أعد المحاولة مرة  اخرى. </span>

                                        @endif

                                    @else

                                        <span style="color:#007bff" class="check-ans-msg">لم تقم بالإجابة على هذا السؤال.</span>

                                    @endif
                                @endif



                                @endif

                            </div>
                            <div id="{{'show_hint_'.$question->hashed_id}}" class="collapse" style="background-color: #f1f1f1; margin-top: 10px; padding: 10px;">
                                <div style="display: inline-block; width: 100%;">
                                    @if($question->question_explanation){!! $question->question_explanation !!} @else <p> لا توجد إضافة .</p> @endif
                                </div>
                            </div>
                            <div class="question-meta">

                                <div style="background-color: {{($question->question_explanation && $questionLogs->preview > 0)?'#28a745' : '#bcbcbc'}};">
                                    <a @if(($question->question_explanation && $questionLogs->preview > 0 && $quiz->duration < 1) || ($quizLogs->status > 0)) href="{{'#show_hint_'.$question->hashed_id}}"  data-toggle="collapse" data-target="{{'#show_hint_'.$question->hashed_id}}" style="cursor: pointer;" @else href="javascript:;" style="cursor: no-drop; color: #e9e8e8;" @endif>
                                        <span style="font-weight: bold;">إضافة</span>
                                        @if($question->question_explanation)
                                            <span class="badge badge-danger">  <i class="fa fa-bell"></i></span>
                                        @endif
                                    </a>
                                    {{-- <div class="qs-info alert alert-danger" role="alert">
                                        ugd hggi ljtgpa fu] ;gi ]i hyfdi ;g;l
                                    </div> --}}
                                </div>
                                @if(!$is_course_element)
                                    <div class="add-to-delayed" style="background-color:{{($quiz->duration < 1)?'#bcbcbc;': '#f8b032;'}}">

                                        @include('partials.quiz_body.delayed', ['q_url' => \Request::url(),'current_page_id' => $current_page_id, 'quiz' => $quiz, 'quizLogs' => $quizLogs, 'question' => $question, 'is_delayed' => !empty($questionLogs)?$questionLogs->delayed:0, 'is_delayed_page' => $is_delayed ])
                                    </div>
                                @endif
                                <div style="background-color: {{($quiz->duration > 0)?'#bcbcbc; cursor: no-drop;': '#007bff; cursor: pointer;'}}">
                                    <a href="javascript:;" class="{{($quiz->duration > 0)?'':'ask_teacher_btn'}}" data-quiz="{{$quiz->hashed_id}}" data-question="{{$question->hashed_id}}" style="font-weight: bold; cursor: {{($quiz->duration > 0)?'no-drop;':'pointer;'}}">
                                        <i class="fa fa-phone"></i>
                                        <span>اسال المعلم</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            @endforeach
        @else
            <div class="alert alert-danger">
                <strong>عفوًا !</strong> لم يتم العثور على اي اسئلة لعرضها.
            </div>
        @endif

        {!! Form::close() !!}
        @if($questions->count())
            {{ $questions->links('quizzes.ajax.tools', ['showAnswer' => $showAnswer, 'quiz' => $quiz, 'quizLogs' => $quizLogs, 'is_delayed' => $is_delayed, 'is_course_element' => $is_course_element, 'course' => $course, 'delayed_questions' => $delayed_questions]) }}

    </div>

    {{-- start questions list --}}
    <div class="col-md-3" style="margin-top: 30px;">
        <div id="side_questions_list" @if(isset($quizTemplate) && $quizTemplate != 'questions') style="display: none;" @endif>
            <div class="card" style="border-top: 3px solid #02475f;">
                <div class="card-body">
                    {{--                     <label for="search">ابحث</label> --}}
                    <div class="form-group">
                        <select data-placeholder="بحث .. ." class="form-control select2-normal" id="questions_select2_list">
                            <option></option>
                            @foreach($questionsList as $key => $value)<option value="{{\Request::url().'?page='.($loop->index+1)}}">{{str_limit(strip_tags($value),27)  }}</option> @endforeach
                        </select>
                    </div>

                    <div>

                        {{ $questions->links('partials.quiz_body.components.questions_list', ['quiz' => $quiz, 'currentQuestion' => $currentQuestion, 'questionsList' => $questionsList]) }}

                        @endif
                        {{--            <ul id="questions-list-menu" class="question-ul-list list-group list-group-unbordered">
                                @foreach($questionsList as $key=>$value)
                              <li class="list-group-item" id="q_list_{{$key}}"><a href="{{\Request::url().'?page='.($loop->index+1)}}">{{str_limit(strip_tags($value),100)  }}</a></li>
                              @endforeach


                            </ul>  --}}
                    </div>






                </div>
            </div>
        </div>
    </div> {{-- end questions list --}}

</div> {{-- end question big container --}}



@php
    $ajax = \Request::ajax()?true:false;
@endphp

@if($ajax)

    <script type="text/javascript">
        $(function () {
            $('.select2-normal').select2({ width: '100%' });

            $('#side_questions_list').show();
            $('#side_add_list').hide();
        });
    </script>
    <script>
        $(function () {
            var storage = localStorage.getItem("question_collapses");
            if (storage) {
                var storageData = JSON.parse(storage);
                console.log(storageData.status);
                console.log(typeof storageData.status);
                if (storageData.target && (storageData.status === true || storageData.status === 'true')) {
                    $(storageData.target).addClass("show").show();
                } else {
                    $(storageData.target).removeClass("show").hide();
                }
            }
            var collapses = $(".collapse-btn");
            $('.collapse-btn').on("click", function () {
                var target = $(this).data('div_target');

                var status = $(target).hasClass('show');
                console.log(target);
                if(status  === true || status === 'true'){
                    // $(this).attr("aria-expanded", true);
                    $(target).removeClass("show").hide(500);
                    status = false;
                }else{
                    $(target).addClass("show").show(500);
                    status = true;

                }


                localStorage.setItem("question_collapses", JSON.stringify({target: target, status: status}));
            });
        });
    </script>

    <script type="text/javascript">
        $( function() {
            var height = $(window).height() * 0.8;
            $('.embededWrapper').find('iframe').attr('src','{{$ifram_url}}');
            // var height = $('#showQuizModal').find('.modal-body').css('min-height');

            $('.embededWrapper').css({"max-height": height, "min-height": height, "min-width": '100%'});
        });
    </script>
    {{-- when student reach to 2\3 time of exam alert to solve his delayed questions --}}
    @if($delayed_questions->count() && $quiz->duration > 0 && $quizLogs->status != 1)
        <script type="text/javascript">
            window.setInterval(alertWarnDelayed, 10000); //every 50 sec
            function alertWarnDelayed() {
                var storage = localStorage.getItem("show_alert_{{$quizLogs->hashed_id}}");

                if(!storage){

                    var hms_quiz_duration = $('.timer').text();   // your input string
                    var a = hms_quiz_duration.split(':'); // split it at the colons
                    console.log(hms_quiz_duration);
// minutes are worth 60 seconds. Hours are worth 60 minutes.
                    var remain_minutes = (+a[0]) * 60 + (+a[1]);
                    var quiz_duration = Number('{{$quiz->duration}}');
                    var warning_time = quiz_duration * (1/3);
                    if(warning_time >= remain_minutes){
                        $('#show_msg_warning_time').show();
                        $('#remainTimeModal').modal('show');
                        localStorage.setItem("show_alert_{{$quizLogs->hashed_id}}", true);
                    } //endif
                }
            }

        </script>
    @endif



@else

    @push('child_scripts')


        <script type="text/javascript">
            $("body").on("select2:select","#questions_select2_list", function (e) {
                window.location.href = e.params.data.id;
            });
        </script>

        <script type="text/javascript">
            $( function() {
                var height = $(window).height();
                $('.embededWrapper').find('iframe').attr('src','{{$ifram_url}}').css({"max-height": height, "min-height": height, "min-width": '100%'});
                // var height = $('#showQuizModal').find('.modal-body').css('min-height');

                $('.embededWrapper').css({"max-height": height, "min-height": height, "min-width": '100%'});
            });
        </script>

        {{-- when student reach to 2\3 time of exam alert to solve his delayed questions --}}
        @if($delayed_questions->count() && $quiz->duration > 0)
            <script type="text/javascript">
                window.setInterval(alertWarnDelayed, 10000); //every 50 sec
                function alertWarnDelayed() {
                    var storage = localStorage.getItem("show_alert_{{$quizLogs->hashed_id}}");

                    if(!storage){
                        var hms_quiz_duration = $('.timer').text();   // your input string
                        var a = hms_quiz_duration.split(':'); // split it at the colons
                        console.log(hms_quiz_duration);
// minutes are worth 60 seconds. Hours are worth 60 minutes.
                        var remain_minutes = (+a[0]) * 60 + (+a[1]);
                        var quiz_duration = Number('{{$quiz->duration}}');
                        var warning_time = quiz_duration * (1/3);
                        if(warning_time >= remain_minutes){
                            $('#show_msg_warning_time').show();

                            $('#remainTimeModal').modal('show');
                            localStorage.setItem("show_alert_{{$quizLogs->hashed_id}}", true);
                        } //endif

                    }
                }

            </script>
        @endif


    @endpush

@endif










