@extends('layouts.lesson') 
@php
$closeItemRoute = route('courses.show', ['id' => $course->hashed_id]);
$breadcrumb = [
	['name' => __('developnet-lms::labels.links.link_page_home'), 'link' => '/'],
	['name' => __('developnet-lms::labels.links.link_page_courses'), 'link' => route('courses.index')],
	['name' => $course->title, 'link' => route('courses.show', ['id' => $course->hashed_id])],
	['name' => __('developnet-lms::labels.links.link_page_lessons'), 'link' => 'javascripts:;'],
	['name' => $lesson->title, 'link' => false],
];		
@endphp

@php
    $lesson_parts = $lesson->lesson_parts()->get();
    $text_explanation = $lesson_parts->where('type', 'text_explanation')->first();
    $video_explanation = $lesson_parts->where('type', 'video_explanation')->first();
    $slide_explanation = $lesson_parts->where('type', 'slide_explanation')->first();
@endphp

@section('css')
<style type="text/css">
	@media (max-width: 580px)
.nav.nav-tabs.custom .nav-link span {
    display: inline !important;
}
</style>
@endsection

@section('content')
			@if($lesson->show_lesson_intro)	
			@if($lesson->preview_video)
			@php
			$lesson_preview_video = \LMS::getEmbededLink($lesson->preview_video);
			@endphp

					@include('components.embeded_media', ['embeded' => $lesson_preview_video['link']])
					@else

					@if($lesson->thumbnail)

					<div class="course-media">
						<img src="{{$lesson->thumbnail}}" alt="{{$lesson->title}}" style="max-width: 100%; height: auto; vertical-align: middle;">
                     </div>
                     @endif

					@endif
		<div class="col-sm-12 text-right mtb-15">
		 @include('components.favourite_action', ['module' => 'lesson', 'module_hash_id' => $lesson->hashed_id])

					  </div>

		<div class="content-item-summary"> 
 {!! $lesson->content !!}
	    </div>
	    @endif

	    				<div class="course-details">
							<ul class="nav nav-tabs custom" id="myTab" role="tablist">
							@if($text_explanation && $text_explanation->status)
							  <li class="nav-item">
							    <a class="nav-link active show" id="lesson_text-tab" data-toggle="tab" href="#lesson_text" role="tab" aria-controls="lesson_text" aria-selected="true">
							    	<i class="fa fa-book"></i><span style="display: inline">شرح نصي </span></a>
							  </li>
							  @endif
							  @if($video_explanation && $video_explanation->status)
							  <li class="nav-item">
							    <a class="nav-link @if(isset($text_explanation->status) && $text_explanation->status < 1) active show @endif" id="lesson_video-tab" data-toggle="tab" href="#lesson_video" role="tab" aria-controls="lesson_video" aria-selected="false">
							    	<i class="fa fa-video-camera"></i> <span style="display: inline">شرح فيديو</span>
							    </a>
							  </li>
							  @endif
							  @if($slide_explanation && $slide_explanation->status)

							  <li class="nav-item">
							    <a class="nav-link {{-- @if((isset($text_explanation->status) && $text_explanation->status < 1) || (isset($video_explanation->status) && $video_explanation->status < 1))active show @endif --}}" id="lesson_slide-tab" data-toggle="tab" href="#lesson_slide" role="tab" aria-controls="lesson_slide" aria-selected="false">
							    	<i class="fa fa-image"></i> <span style="display: inline">البطاقات</span>
							    </a>
							  </li>
							  @endif

							</ul>
							<div class="tab-content custom" id="myTabContent">
								@if($text_explanation && $text_explanation->status)
							  	<div class="tab-pane fade show active" id="lesson_text" role="tabpanel" aria-labelledby="overview-tab">
                             {!! str_replace('class="alrabeh', 'allowfullscreen sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin allow-top-navigation" class="alrabeh', $text_explanation->content) !!}

							  	</div>
							  	@endif
							  	@if($video_explanation && $video_explanation->status)
							 	<div class="tab-pane fade @if(isset($text_explanation->status) && $text_explanation->status < 1) active show @endif" id="lesson_video" role="tabpanel" aria-labelledby="lesson_video-tab">


					@include('components.embeded_media', ['embeded' => $video_explanation->embeded_url])
		
								</div>
								@endif
								@if($slide_explanation && $slide_explanation->status)
							  	<div class="tab-pane fade {{-- @if((isset($text_explanation->status) && $text_explanation->status < 1) || (isset($video_explanation->status) && $video_explanation->status < 1))active show @endif --}}" id="lesson_slide" role="tabpanel" aria-labelledby="lesson_slide-tab">
			@include('components.embeded_media', ['embeded' => $slide_explanation->embeded_url, 'height' => '100vh'])

							  	</div>
							  	@endif

							</div>
						</div>

	    @if(countData($courseLogs)) 
	    @if($courseLogs->status < 1) 
   
	    <div class="col-sm-12 text-center" style="margin-bottom: 30px;">
	    	 {!! Form::model($lesson, ['url' => route('courses.lesson_completed', ['course_id' => $course->hashed_id, 'lesson_id'=> $lesson->hashed_id]),'method'=>'PUT','files'=>true]) !!}

	    	 @if($enrollStatus['success'] && $enrollStatus['status'] == 1)
               <button type="submit" class="btn btn-danger">{{__('developnet-lms::labels.buttons.btn_uncompleted')}}</button>
	    	@else
	    	  <button type="submit" class="btn btn-success">{{__('developnet-lms::labels.buttons.btn_completed')}}</button>

	    	@endif
	    	

	    	 {!! Form::close() !!}
	    </div>
	    @endif
	    @endif

	   {{--  <div class="p-30">
	    	<div class="course-add-rate">
				<ul>
					<li>
						Review this lesson
					</li>
					<li>
					<fieldset class="rating">
					    <input type="radio" id="star5" name="rating" value="5" />
					    <label class = "full" for="star5" title="Awesome - 5 stars"></label>

					    <input type="radio" id="star4half" name="rating" value="4 and a half" />
					    <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

					    <input type="radio" id="star4" name="rating" value="4" />
					    <label class = "full" for="star4" title="Pretty good - 4 stars"></label>

					    <input type="radio" id="star3half" name="rating" value="3 and a half" />
					    <label class="half" for="star3half" title="Meh - 3.5 stars"></label>

					    <input type="radio" id="star3" name="rating" value="3" />
					    <label class = "full" for="star3" title="Meh - 3 stars"></label>

					    <input type="radio" id="star2half" name="rating" value="2 and a half" />
					    <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

					    <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>

					    <input type="radio" id="star1half" name="rating" value="1 and a half" />
					    <label class="half" for="star1half" title="Meh - 1.5 stars"></label>

					    <input type="radio" id="star1" name="rating" value="1" />
					    <label class = "full" for="star1" title="Sucks big time - 1 star"></label>

					    <input type="radio" id="starhalf" name="rating" value="half" />
					    <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
					</fieldset>
					</li>
				</ul>
			</div>
			<div class="course-review-comments">
				<ul>
					<li class="course-review-comment">
						<div class="media">
							<img src="/assets/themes/developnet-lms/img/user.png">
							<div class="media-body">
								<div class="comment-stars">
									<span>Mikle</span>
									<div class="review-stars">
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
								</div>
								</div>
								<div class="comment-title">
									Beautiful theme - Awesome plugin
								</div>
								<p>Admin bar avatar Minhluu
								Beautiful theme - Awesome plugin

								5 stars for this theme too. Education WP theme brings the best LMS experience ever with super friendly UX and complete eLearning features. Really satisfied.
							</p> 
							<span class="reply">@lang('developnet-lms::labels.spans.span_reply_comment')</span>
							<div class="reply-form contact-form">
								<form>
					       		<div class="form-group form-inline">
					              <div class="col-lg-12">
					                <textarea class="form-control" name="Message" placeholder="التعليق" style="height: 150px;"></textarea>
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Name" placeholder="الاسم">
					              </div>
					              <div class="col-md-4">
					                <input type="mail" class="form-control" name="Mail" placeholder="الايميل">
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Title" placeholder="عنوان التعليق">
					              </div>
					              <div class="col-lg-12">
					                <input type="submit" name="Submit" value="تعليق" title="اhvshg" class="colored-btn">
					              </div>
					            </div>
				            	</form>
				            </div>
							</div>
						</div>
					</li>
					<li class="course-review-comment">
						<div class="media">
							<img src="/assets/themes/developnet-lms/img/user.png">
							<div class="media-body">
								<div class="comment-stars">
									<span>Mikle</span>
									<div class="review-stars">
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
								</div>
								</div>
								<div class="comment-title">
									Beautiful theme - Awesome plugin
								</div>
								<p>Admin bar avatar Minhluu
								Beautiful theme - Awesome plugin

								5 stars for this theme too. Education WP theme brings the best LMS experience ever with super friendly UX and complete eLearning features. Really satisfied.
							</p>
							<span class="reply">@lang('developnet-lms::labels.spans.span_reply_comment')</span>
							<div class="reply-form contact-form">
								<form>
					       		<div class="form-group form-inline">
					              <div class="col-lg-12">
					                <textarea class="form-control" name="’Message" placeholder="التعليق" style="height: 150px;"></textarea>
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Name" placeholder="الاسم">
					              </div>
					              <div class="col-md-4">
					                <input type="mail" class="form-control" name="Mail" placeholder="الايميل">
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Title" placeholder="عنوان التعليق">
					              </div>
					              <div class="col-lg-12">
					                <input type="submit" name="Submit" value="تعليق" title="اhvshg" class="colored-btn">
					              </div>
					            </div>
				            	</form>
				            </div>
							</div>
						</div>
					</li>
					<li class="course-review-comment">
						<div class="media">
							<img src="/assets/themes/developnet-lms/img/user.png">
							<div class="media-body">
								<div class="comment-stars">
									<span>Mikle</span>
									<div class="review-stars">
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
								</div>
								</div>
								<div class="comment-title">
									Beautiful theme - Awesome plugin
								</div>
								<p>Admin bar avatar Minhluu
								Beautiful theme - Awesome plugin

								5 stars for this theme too. Education WP theme brings the best LMS experience ever with super friendly UX and complete eLearning features. Really satisfied.
							</p>
							<span class="reply">@lang('developnet-lms::labels.spans.span_reply_comment')</span>
							<div class="reply-form contact-form">
								<form>
					       		<div class="form-group form-inline">
					              <div class="col-lg-12">
					                <textarea class="form-control" name="Message" placeholder="التعليق" style="height: 150px;"></textarea>
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Name" placeholder="الاسم">
					              </div>
					              <div class="col-md-4">
					                <input type="mail" class="form-control" name="Mail" placeholder="الايميل">
					              </div>
					              <div class="col-md-4">
					                <input type="text" class="form-control" name="Title" placeholder="عنوان التعليق">
					              </div>
					              <div class="col-lg-12">
					                <input type="submit" name="Submit" value="تعليق" title="اhvshg" class="colored-btn">
					              </div>
					            </div>
				            	</form>
				            </div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="add-comment contact-form ">
		       		<h4>@lang('developnet-lms::labels.headings.text_add_comment')</h4>
			       	<form>
			       		<div class="form-group form-inline">
			              <div class="col-lg-12">
			                <textarea class="form-control" name="Message" placeholder="{{__('developnet-lms::attributes.inputs.input_comment')}}" style="height: 250px;"></textarea>
			              </div>
			              <div class="col-md-4">
			                <input type="text" class="form-control" name="Name" placeholder="{{__('developnet-lms::attributes.inputs.input_name')}}">
			              </div>
			              <div class="col-md-4">
			                <input type="mail" class="form-control" name="Mail" placeholder="{{__('developnet-lms::attributes.inputs.input_email')}}">
			              </div>
			              <div class="col-md-4">
			                <input type="text" class="form-control" name="Title" placeholder="{{__('developnet-lms::attributes.inputs.input_comment_title')}}">
			              </div>
			              <div class="col-lg-12">
			                <input type="submit" name="Submit" value="{{__('developnet-lms::attributes.inputs.btn_comment')}}" title="اhvshg" class="colored-btn">
			              </div>
			            </div>
		            </form>
	       	</div>
	    </div> --}}
	    
@endsection

