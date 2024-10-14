@extends('layouts.master')

@section('css')
 {!! Theme::css('css/pages.css') !!}
@endsection

@section('content')	

	{{--@include('partials.banner')	--}}
	<!-- Page Content-->
	<!-- Quiz Start-->
	{{-- <div class="exam-section single-quiz">
		<div >
			<h3 class="quiz-title">Easy Quiz Eample</h3>
			<p>A quiz about the plugin built by the plugin.</p>
			<a href="quiz.html" class="btn btn-success">Start Quiz</a>
		</div>
	</div> --}}
	<br><br><br><br><br>
	<!-- Quiz--> 
<div class="col-md-12">
@include('quizzes.partials.quiz_body', ['template' => $quizTemplate])
</div>


@endsection

@section('css')
	
@endsection
 
@section('js')

@endsection