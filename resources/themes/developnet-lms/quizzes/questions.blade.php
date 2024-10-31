@extends('layouts.master')

@section('css')
{!! Theme::css('css/pages.css') !!}

<style type="text/css">
  @media (min-width: 576px) {

    .quiz-video-modal-dialog {
      max-width: 70% !important;
      margin: 1.75rem auto;
    }

  }


  .modal-header .btnGrp {
    position: absolute;
    top: 8px;
    right: 10px;
  }


  .min {
    width: 250px;
    height: 35px;
    overflow: hidden !important;
    padding: 0px !important;
    margin: 0px;

    float: left;
    position: static !important;
  }

  .min .modal-dialog,
  .min .modal-content {
    height: 100%;
    width: 100%;
    margin: 0px !important;
    padding: 0px !important;
  }

  .min .modal-header {
    height: 100%;
    width: 100%;
    margin: 0px !important;
    padding: 3px 5px !important;
  }

  .display-none {
    display: none;
  }

  button .fa {
    font-size: 16px;
    margin-left: 10px;
  }

  .min .fa {
    font-size: 14px;
  }

  .min .menuTab {
    display: none;
  }

  button:focus {
    outline: none;
  }

  .minmaxCon {
    height: 35px;
    bottom: 1px;
    left: 1px;
    position: fixed;
    right: 1px;
    z-index: 9999;
  }
</style>
@endsection
@php
$relatedIds = $quiz->categories->pluck('id')->toArray();
$relatedQuizzes = \Modules\Components\LMS\Models\Quiz::whereHas('categories', function ($q)use ($relatedIds) {
$q->whereIn('id',$relatedIds);
})->where('status', true);

@endphp

@section('content')
@php
$authUser = new \Modules\Components\LMS\Models\UserLMS;
if(Auth::check()){
$authUser = \Modules\Components\LMS\Models\UserLMS::find(Auth()->id());
}
@endphp

@include('partials.banner', ['page_title' => $quiz->title, 'breadcrumb' => $viewBreadcrumb])

<section class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-md-12 course-wrap" @if($subscriptionStatus['success']) style="display: none;" @endif>
        @php

        $moduleArray = ['module' => 'quiz','module_id' => $quiz->id, 'user' => $authUser];
        $planned = \Subscriptions::planned($moduleArray);

        @endphp

        @if($planned['success'] && !$subscriptionStatus['success'])
        <div class="message message-warning alert alert-warning" role="alert" role="alert">
          <i class="fa"></i>
          <p> @lang('developnet-lms::labels.messages.planned_quiz_hint') </p>
        </div>
        @endif

        {{-- <div class="course-title">
                            <h3>{{$quiz->title}}</h3>
      </div> --}}
      <div class="course-guide-info">
        <ul class="course-guide-list">
          @if($quiz->categories->count())
          <li>
            <div class="title-span"><span>
                @lang('developnet-lms::labels.spans.span_category')
              </span></div>
            <div class="span-link">
              @foreach($quiz->categories as $category)
              <span><a href="{{ route('categories.show', $category->id) }}">{{$category->name}}</a></span>
              @endforeach
            </div>
          </li>
          @endif
          {{-- <li>
                                                            <div class="title-span"><span>
                                                            @lang('developnet-lms::labels.spans.span_review')</span></div>
                                                            <div class="review-rates ">
                                                                <div class="review-stars">
                                                                    <span class="fa fa-star checked"></span>
                                                                    <span class="fa fa-star checked"></span>
                                                                    <span class="fa fa-star checked"></span>
                                                                    <span class="fa fa-star"></span>
                                                                    <spanstar"></span>
                                                                </d class="fa fa-iv>
                                                                 <span class="review-num">(0 @lang('developnet-lms::labels.spans.span_reviews'))</span>
                                                            </div>
                                                        </li>  --}}
        </ul>
        @php



        @endphp
        {{-- @include('components.favourite_action', ['module' => 'quiz', 'module_hash_id' => $quiz->hashed_id])
                        <div class="take-it"> --}}

        @php

        if($quiz->sale_price > 0){

        $quizPrice = $quiz->sale_price;

        }else{
        $quizPrice = $quiz->price;
        }



        @endphp



        @if(!$subscriptionStatus['success'])

        {!! Form::model($quiz, ['url' => route('subscriptions.subscribe', ['module_id' => $quiz->hashed_id, 'module'=> 'quiz']),'method'=>'POST','files'=>true]) !!}
        {{-- @if($quizPrice > 0)
                                    <span class="money">{{$quizPrice}}$ </span>
        @if($quiz->sale_price > 0)
        <span class="subject-value-deleted">{{$quiz->price}} $</span>
        @endif
        @else
        <span class="take-free "> @lang('developnet-lms::labels.spans.span_free')</span>
        @endif --}}

        @if($subscriptionStatus['success'])
        <button type="submit"
          class="colored-btn-red">@lang('developnet-lms::labels.spans.span_book_quiz')</button>

        @else

        @endif

        {!! Form::close() !!}

        @else

        {{-- <a href="javascript:;" class="colored-btn-red"
                                   style="background-color: #f8b032; ">@lang('developnet-lms::labels.spans.span_booked')</a> --}}


        {{-- <a href="{{route('quizzes.handel_quiz', ['quiz' => $quiz->hashed_id])}}" class="btn btn-danger"> عرض الاختبار</a> --}}

        @endif

      </div>
    </div>
  </div>

  <div class="row">
    <div class="course-details col-md-12" style="margin: 5px 0; ">
      {{-- @include('partials.quiz_body.quiz_iframe', ['quiz' => $quiz]) --}}
      @include('partials.quiz_body.index')



    </div>




  </div>

  @if($relatedQuizzes->count())
  <div class="page-side-title">
    <h4>@lang('developnet-lms::labels.headings.text_related_quizzes')</h4>
  </div>
  <div class="other-courses">
    <div class="row">
      @foreach($relatedQuizzes->get() as $relatedQuiz)

      <div class="col-md-4 col-sm-6">
        @include('quizzes.partials.grid_quiz_1', ['quiz' => $relatedQuiz])
      </div>
      @endforeach
    </div>
  </div>
  @endif



  <!-- Side bar-->

  {{-- @include('partials.sidebar') --}}

  </div>

</section>


@endsection
@push('child_after_content')
@php
$hint_videos = $quiz->hint_videos;
@endphp
@if($hint_videos->count())
<div class="container_2">
  <!-- Modal -->
  <div class="modal fade quiz-video-modal" id="modal-1" trole="dialog">
    <div class="modal-dialog quiz-video-modal-dialog" role="document" style="background: #fff;">
      <div class="modal-content">
        <div class="modal-header">
          <div class="float-right">
            <button type="button" class="close_model modalVideoClose btn btn-secondary btn-sm" data-target="#modal-1"> <i class='fa fa-times' style="margin: 0px;"></i> </button>
            <button class="close_model modalMinimize btn btn-secondary btn-sm" data-modal_id="#modal-1"> <i class='fa fa-minus' style="margin: 0px;"></i> </button>

          </div>

          <div class="btn-group float-right">
            <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-desktop"></i>
            </button><button type="button" class="show-quiz-video btn_close_video_container" style="display: none;"><i class="fa fa-times-circle"></i></button>

            <div class="dropdown-menu dropdown-menu-right" style="width: 253px;">
              @foreach($hint_videos as $row)

              <a class="show-embeded-video {{($loop->index < 1)?'active-video-item':''}}  dropdown-item" href="javascript:;" data-url="{{$row->file_url}}">{{$row->title}}</a>
              @endforeach

            </div>
          </div>
          {{-- <h4 class="modal-title" id="quizVideoMdalLabel">Minimize/Maximize modal with content</h4> --}}
        </div>
        <div class="modal-body">
          @if($hint_videos->count())
          <div id="show_quiz_video_container">
            @include('components.embeded_media', ['embeded' => $hint_videos->first()->file_url])
          </div>
          @endif



        </div>

      </div>
    </div>
  </div>
</div>
<div class="minmaxCon" style="display: none;">

  <div class="modal fade quiz-video-modal show min" id="modal-2" trole="dialog" style="padding-right: 17px; display: block;">
    <div class="modal-dialog quiz-video-modal-dialog" role="document" style="background: #fff;">
      <div class="modal-content">
        <div class="modal-header">
          <div class="float-right">
            <button type="button" class="close_model modalVideoClose btn btn-secondary btn-sm" data-target="#modal-2"> <i class="fa fa-times" style="margin: 0px;"></i> </button>
            <button class="close_model modalMaximize btn btn-secondary btn-sm"> <i class="fa fa-clone" style="margin: 0px;"></i> </button>

          </div>
          <div class="btn-group float-right">
            <button class="btn btn-danger btn-sm" type="button">
              <i class="fa fa-desktop" style="margin-left: 0px;"></i>
            </button>
          </div>

        </div>
        <div class="modal-body">
        </div>



      </div>

    </div>
  </div>
</div>

@endif

@endpush
@section('js')


<script>
  $(document).ready(function() {
    $("body").on("click", ".modalMinimize", function() {
      $('#modal-1').modal('hide');
      $('.minmaxCon').show();
    });
    $("body").on("click", ".modalMaximize", function() {
      $('#modal-1').modal('show');
      $('.minmaxCon').hide();

    });
    $("body").on("click", ".modalVideoClose", function() {
      $("#modal-1 iframe").attr("src", $("#modal-1 iframe").attr("src"));
      $('#modal-1').modal('hide');
      $('.minmaxCon').hide();
    });


    var $content, $modal, $apnData, $modalCon;

    $content = $(".min");


    //To fire modal
    $("body").on("click", ".mdlFire", function(e) {
      e.preventDefault();

      var $id = $(this).attr("data-target");
      $($id).modal({
        backdrop: false,
        keyboard: false
      });
      $('.minmaxCon').hide();

    });


  });
</script>
@endsection

{{-- @section('after_content')
@include('partials.quiz_body.show_modal', ['modal_id' => 'showQuizModal'])

@endsection --}}