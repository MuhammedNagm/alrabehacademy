@extends('layouts.iframe')


@section('content') 
<section class="page-content">
<div class="container">
	  <div class="row">
                    <div class="course-details col-md-12" style="margin: 5px 0; ">
   @include('partials.quiz_body.index', ['show_quiz_title' => false])
</div>
</div>

</section>
@endsection

@section('js')


@endsection

