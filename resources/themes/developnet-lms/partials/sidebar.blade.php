@php
$lastCreatedPlans= \LMS::leatestPlans();
@endphp
<div class="col-md-3 side-bar">

	@if(isset($show_plan_grid))
	<div class="form-group">
	<select class="js-data-course-quiz-ajax form-control" style="width: 100%" id="js-data-course-quiz-ajax-id"></select>
   </div>
	@include('plans.partials.grid_plan_1', ['plan' => $plan, 'is_subscribed' => $is_subscribed])
	@endif
	<div class="side-news">
		
		@if($lastCreatedPlans->count())
			<div class="page-side-title">
				<h4>الباقات المضافة مؤخراً</h4>
			</div>
			

			@foreach($lastCreatedPlans->get() as $row)
			@php

			if($row->sale_price > 0){
				$rowPrice = $row->sale_price;
			}else{
				$rowPrice = $row->price;
			}

			@endphp

				<div class="media">
					<img src="{{$row->thumbnail}}">
					<div class="media-body">
						<div class="news-title">
							<a href="{{route('plans.show',$row->hashed_id)}}">{{$row->title}}</a>
						</div>

					</div>	
				</div>
			@endforeach
		@endif

	</div>
	@if(\Settings::get('sidebar_bannar_img'))
	<div class="side-adv">
		<a href="{{\Settings::get('sidebar_bannar_url')}}"><img src="{{\Settings::get('sidebar_bannar_img')}}"></a>							
	</div>
	@endif
</div>

@push('child_scripts')
@if(isset($show_plan_grid) && $plan)
<script type="text/javascript">
$(function(){
$('.js-data-course-quiz-ajax').select2({
placeholder: 'عن ماذا تبحث ؟!',
lang: 'ar',
// theme: "material",
allowClear: true,
ajax: {
url: '{{url('/packages/'.$plan->hashed_id.'/search')}}',
dataType: 'json',
delay: 250,
processResults: function (data) {
return {
	results:data,
};
},
cache: false
},
  minimumInputLength: 1,

	templateResult: formatRepo,

});



});


function formatRepo (repo) {


  return repo.text;
}

function formatRepoSelection (repo) {
  return repo.text;
}

$("#js-data-course-quiz-ajax-id").on("select2:select", function (e) {
    
window.open(e.params.data.id);

});

</script>
@endif
@endpush