	@php
	$ifram_url = route('quizzes.lood_in_ifram', ['quiz' => $quiz->hashed_id]);
	@endphp
	<iframe src='{{$ifram_url}}' frameborder='0' allowfullscreen width="100%" scrolling="no" id="myIframe"></iframe>
