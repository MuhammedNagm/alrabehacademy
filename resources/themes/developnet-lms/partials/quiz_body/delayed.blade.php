	@php
	$check_delayed = $is_delayed;

	if($check_delayed){
		$fav_text = 'سؤال مؤجل';
		$fav_color = '';
	}else{
		$fav_text = 'تأجيل';
		$fav_color = '';
	}

	if ($quiz->duration < 1) {
		$fav_color = '#e6e7e8;';
	}

   @endphp
		<a id="press-delayed-btn-{{$question->hashed_id}}" href="javascript:;" class="{{($quiz->duration < 1)?'':'add-to delayed-question'}}" title="{{$fav_text}}" data-url="{{route('quizzes.markAsDelayed', ['quiz_id' => $quiz->hashed_id, 'logs_id' => $quizLogs->hashed_id, 'question_id' => $question->hashed_id ])}}" style="color:{{$fav_color}}; font-weight: bold; cursor: {{($quiz->duration < 1)?'no-drop;':'pointer;'}}" data-isDelayed="{{$check_delayed}}" question="{{$question->hashed_id}}" quiz="{{$quiz->hashed_id}}" data-q_url="{{isset($q_url)?$q_url:''}}" data-current_page_id="{{isset($current_page_id)?$current_page_id:''}}" data-is_delayed_page="{{isset($$is_delayed)?$$is_delayed:0}}"> <i class="fa fa-bookmark-o" aria-hidden="true"></i>	
					<span>{{$fav_text}}</span>	  				
		</a>		
					  
