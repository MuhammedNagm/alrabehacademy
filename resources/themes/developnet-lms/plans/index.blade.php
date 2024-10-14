@extends('layouts.master')

@section('css')
 {!! Theme::css('css/pages.css') !!}
@endsection

@php
$breadcrumb = [
	['name' => __('developnet-lms::labels.links.link_page_home'), 'link' => '/'],
	['name' => __('developnet-lms::labels.links.link_page_plans'), 'link' => false]
];
@endphp

@section('content')

	@include('partials.banner', ['page_title' =>  __('developnet-lms::labels.links.link_page_plans'), 'breadcrumb' => $breadcrumb])
	<!-- Page Content-->
	<section class="pricing-sec pt-50">
		<div class="container">
			@if($plans->count())
			<div class="page-side-title">
				<h3>@lang('developnet-lms::labels.headings.text_all_plans')</h3>
			</div>
			<div class="pricing-grids row">


				@foreach($plans->get() as $plan)

				@php
				$is_subscribed = \Subscriptions::is_subscribed([
					'user' => userLMS(),
					'module' => 'plan',
					'module_id' => $plan->id
				]);
				@endphp
				@php
				if($plan->sale_price > 0){
                 $plan_price = $plan->sale_price?:0;
				}else{
                  $plan_price = $plan->price;
				}
				 
				@endphp

				<div class="col-sm-6 col-lg-3">
					<div class="pricing-grid {{$plan->is_featured? 'colored':'' }} {{$is_subscribed?'subscribed':''}}">

						<div class="pricing_grid">
							<div class="pricing-top ">
								<h3>{{$plan->title}}</h3>
							</div>
								<div class="pricing-info ">
                                @if($plan_price > 0)
								<p>{{$plan_price}}</p>
								@else
								<p>@lang('developnet-lms::labels.spans.span_free')</p>
								@endif
                                
								</div>
							<div class="pricing-bottom">
								<div class="pricing-bottom-bottom">
									<p>{!! $plan->content !!}</p>
								</div>
								<div class="buy-btn">
										<a href="{{route('plans.show', $plan->hashed_id)}}">
										عرض الباقة
										</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@else
			@lang('developnet-lms::labels.headings.text_plans_msg')
			<br><br>
			@endif
		</div>
	</section>
@endsection
