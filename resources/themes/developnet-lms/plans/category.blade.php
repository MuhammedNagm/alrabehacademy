@extends('layouts.master')

@section('css')
{!! Theme::css('css/pages.css') !!}
@endsection

@section('content')
@php
$breadcrumb = [
['name' => __('developnet-lms::labels.links.link_page_plans'), 'link' => url('/packages')],
['name' => $plan->title, 'link' => route('plans.show', $plan->hashed_id)]
];
$parentLinks = [];
$currentCat = $category;
for ($i = 0; $i <= 100; $i++) {
	if ($parent=$currentCat->parentCategory) {
	$parentLinks[] = ['name' => $parent->name, 'link' => route('plans.category', ['plan' => $plan->hashed_id, 'category' => $parent->hashed_id])];
	$currentCat = $parent;

	} else {
	break;
	}
	}

	$breadcrumb = array_merge($breadcrumb, array_reverse($parentLinks));

	$breadcrumb[] = ['name' => $category->name, 'link' => false];

	@endphp

	@include('partials.banner', ['page_title' => $category->name, 'breadcrumb' => $breadcrumb])

	@php
	$is_subscribed = \Subscriptions::is_subscribed([
	'user' => userLMS(),
	'module' => 'plan',
	'module_id' => $plan->id
	]);
	@endphp

	<section class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-9 course-wrap">
					@if($child_categories->count())
					<div class="page-side-title">
						<h3>الاقسام الفرعية</h3>
					</div>
					<div class="other-courses categories">
						<div class="row">

							@foreach($child_categories as $category)

							<div class="col-md-4 col-sm-6">
								@include('plans.partials.grid_categories', ['category' => $category, 'plan' => $plan])
							</div>

							@endforeach




						</div>
					</div>
					<hr>
					@endif


					@if($courses->count())
					<div class="page-side-title">
						<h3>الدورات التدريبية</h3>
						{{-- <h3>@lang('developnet-lms::labels.headings.text_courses_related_category')</h3> --}}
					</div>
					<div class="other-courses">

						<div class="row">
							@foreach($courses as $course)

							<div class="col-lg-11">
								@include('courses.partials.list_courses_1', ['course' => $course])
							</div>

							@endforeach
						</div>

						<hr>
						<div class="row">
							{{ $courses->links('partials.paginator') }}

						</div>

					</div>
					@endif
					<br><br>
					<div class="row">
						<div class="col-lg-11">
							@if($quizzes->count())
							<h3>الاختبارات</h3>
							{{-- <h3>@lang('developnet-lms::labels.headings.text_quizzes_related_category')</h3> --}}

						</div>
						<div class="other-courses">

							<div class="row">
								@foreach($quizzes as $quiz)
								<div class="col-lg-11">
									@php
									$quizBreadcrumb = $breadcrumb;
									$quizBreadcrumb[count($quizBreadcrumb) - 1]['link'] = route('plans.category', ['plan' => $plan->hashed_id, 'category' => $category->hashed_id]);
									@endphp
									@include('quizzes.partials.list_quiz', ['quiz' => $quiz, 'breadcrumb' => $quizBreadcrumb])
								</div>
								@endforeach
							</div>

							<hr>
							<div class="row">
								{{ $quizzes->links('partials.paginator') }}

							</div>

						</div>
						@endif

					</div>

					<br><br>
					<div class="row">
						<div class="col-lg-11">
							@if($books->count())
							<div class="page-side-title">
								<h3>الكتب </h3>
							</div>
							<div class="other-courses">

								<div class="row">
									@foreach($books as $book)

									@include('books.partials.list_books', ['book' => $book])

									@endforeach
								</div>

								<hr>
								<div class="row">
									{{ $books->links('partials.paginator') }}

								</div>

							</div>
							@endif
						</div>
					</div>

				</div>
				@include('partials.sidebar', ['show_plan_grid' => true, 'plan' => $plan, 'is_subscribed' => $is_subscribed])
			</div>
		</div>
	</section>


	@endsection