@php
$subQuizLogs = null;
$sub_quiz = $quiz->children()->first();

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
$redirect_sub_quiz = \Request::fullUrl();
$redirect = \Request::fullUrl();
if($sub_quiz){
  $redirect_sub_quiz = str_replace($quiz->hashed_id,$sub_quiz->hashed_id,$redirect);

}
@endphp

		<div class="col-md-12" id="startQuizForm">
							<div class="start-quiz-group" id="start_quiz_form">
								<img class="image-responsive" style="width: 100%; height: auto; min-height: 160px" src="{{Theme::url('img/start-quiz.jpg')}}">
								<input type="hidden" name="course_id" value="{{$course?$course->hashed_id:null}}">
								<div class="start-quiz-btns">
									@if($sub_quiz)

							        <a href="javascript:;" class="start_quiz_btn btn btn-success" data-quiz_duration="{{$quiz->duration}}" data-parent_id="#start_quiz_form" data-form_url="{{route('quizzes.enroll_quiz', ['quiz' => $quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}" data-redirect="{{$redirect}}">فاهم</a>
							       @if($subQuizLogs)
									<a href="{{route('quizzes.quizPage', ['quiz' => $sub_quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}" class="btn btn-danger" data-quiz_duration="{{$sub_quiz->duration}}" data-parent_id="#start_quiz_form">اختبر نفسك</a>

									@else
									<a href="javascript:;" class="start_quiz_btn btn btn-danger" data-quiz_duration="{{$sub_quiz->duration}}" data-parent_id="#start_quiz_form" data-form_url="{{route('quizzes.enroll_quiz', ['quiz' => $sub_quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}" data-redirect="{{$redirect_sub_quiz}}">اختبر نفسك</a>

									@endif

									@else
								<a href="javascript:;" class="start_quiz_btn btn btn-success" data-quiz_duration="{{$quiz->duration}}" data-parent_id="#start_quiz_form" data-form_url="{{route('quizzes.enroll_quiz', ['quiz' => $quiz->hashed_id, 'course' => $course?$course->hashed_id:''])}}"  data-redirect="{{$redirect}}">ابدأ  الآن</a>

									@endif
								</div>
							</div>
		</div>

		 	<style>
				.start-quiz-group{
					position: relative;
					display: inline;
				}
				.start-quiz-btns{
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%,-50%);
				}
				.start-quiz-btns a{
					margin: 2px;
					box-shadow: 1px 1px 0px 0px #ffffff9e
				}
			</style>

		<br>

