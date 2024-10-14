		<section class="subjects" style="background-color: #fff;">
			<div class="container">
				<div class=" row ">
					<div class="title-center"> <h3 class="custom-title">الديوانية</h3></div>
				</div>
			</div>
			<div class="container">
				<div class="row fadeInUp wow animated" data-wow-delay="0.5s">
					<div  class="owl-carousel owl-theme customCarousel">
					@foreach(\Modules\Components\LMS\Models\StudentResult::where('status',1)->get() as $result_row)	
					 	<div class="item subject-block" style="padding-bottom: 0px;">
						  	<a href="javascript:;" class="subject-img load_student_result" data-url="{{route('student_result.ajax_load',['student' => $result_row->hashed_id])}}">
						  		<img src="{{$result_row->thumbnail}}" style="margin-bottom: 0px;">
						  		<span class="subject-read-more" style="top: 50%;">معاينة</span>
						  	</a>
						</div>
					@endforeach	

					</div>
				</div>

			</div>	
		</section>