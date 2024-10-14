<?php

// LMS
Breadcrumbs::register('lms', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('LMS::module.lms.title'));
});

//Course
Breadcrumbs::register('lms_courses', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.course.title"), url(config('lms.models.course.resource_url')));
});

Breadcrumbs::register('lms_course_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_courses');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_course_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_courses');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Coupon Group
Breadcrumbs::register('lms_coupon_group_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_coupons_groups');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_coupons_groups', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.coupon_group.title'), url(config('lms.models.coupon_group.resource_url')));
});

//Coupon
Breadcrumbs::register('lms_coupon_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_coupons');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_coupons', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.coupon.title'), url(config('lms.models.coupon.resource_url')));
});

//Lesson
Breadcrumbs::register('lms_lessons', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.lesson.title"), url(config('lms.models.lesson.resource_url')));
});

Breadcrumbs::register('lms_lesson_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_lessons');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_lesson_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_lessons');
    $breadcrumbs->push(view()->shared('title_singular'));
});
 


//quizzes
Breadcrumbs::register('lms_quizzes', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.quiz.title"), url(config('lms.models.quiz.resource_url')));
});

Breadcrumbs::register('lms_quiz_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_quizzes');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_quiz_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_quizzes');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//questions
Breadcrumbs::register('lms_questions', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.question.title"), url(config('lms.models.question.resource_url')));
});

Breadcrumbs::register('lms_question_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_questions');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_question_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_questions');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//quiz => questions


Breadcrumbs::register('lms_quiz_questions', function ($breadcrumbs, $quiz) {
    $breadcrumbs->parent('lms_quizzes');
    $breadcrumbs->push($quiz->title, route(config('lms.models.quiz_question.resource_route'), ['quiz' => $quiz->hashed_id]));
});

Breadcrumbs::register('lms_quiz_question_create_edit', function ($breadcrumbs, $quiz) {
    $breadcrumbs->parent('lms_quiz_questions', $quiz);
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_quiz_question_show', function ($breadcrumbs, $quiz) {
    $breadcrumbs->parent('lms_quiz_questions', $quiz);
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Plans
Breadcrumbs::register('lms_plans', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.plan.title"), url(config('lms.models.plan.resource_url')));
});

Breadcrumbs::register('lms_plan_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_plans');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_plan_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_plans');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Lesson
Breadcrumbs::register('lms_testimonials', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans("LMS::module.testimonial.title"), url(config('lms.models.testimonial.resource_url')));
});

Breadcrumbs::register('lms_testimonial_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_testimonials');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_testimonial_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_testimonials');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//
////News
//Breadcrumbs::register('news', function ($breadcrumbs) {
//    $breadcrumbs->parent('lms');
//    $breadcrumbs->push(trans('LMS::module.news.title'), url(config('lms.models.news.resource_url')));
//});
//
//Breadcrumbs::register('news_create_edit', function ($breadcrumbs) {
//    $breadcrumbs->parent('news');
//    $breadcrumbs->push(view()->shared('title_singular'));
//});
//
//Breadcrumbs::register('news_show', function ($breadcrumbs) {
//    $breadcrumbs->parent('news');
//    $breadcrumbs->push(view()->shared('title_singular'));
//});

//Category
Breadcrumbs::register('lms_categories', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.category.title'), url(config('lms.models.category.resource_url')));
});

Breadcrumbs::register('lms_category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Tag
Breadcrumbs::register('lms_tags', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.tag.title'), url(config('lms.models.tag.resource_url')));
});

Breadcrumbs::register('lms_tag_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_tags');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Invoice 
// Breadcrumbs::register('lms_invoices', function ($breadcrumbs) {
//     $breadcrumbs->parent('lms');
//     $breadcrumbs->push(trans('LMS::module.invoice.title'), url(config('lms.models.invoice.resource_url')));
// });

// Breadcrumbs::register('lms_invoice_create_edit', function ($breadcrumbs) {
//     $breadcrumbs->parent('lms_invoices');
//     $breadcrumbs->push(view()->shared('title_singular'));
// });



//invoice 2

Breadcrumbs::register('lms_invoices', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.invoice.title'));
});


//subscription

Breadcrumbs::register('lms_subscriptions', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.subscription.title'));
});

//Certificates Templates
Breadcrumbs::register('lms_certificates', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.certificate.title'), url(config('lms.models.certificate.resource_url')));
});

Breadcrumbs::register('lms_certificate_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_certificates');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_certificate_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_certificates');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//books

Breadcrumbs::register('books_management', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('LMS::module.book.title'), url(config('lms.models.book.resource_url')));
});


Breadcrumbs::register('books_management_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('books_management');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('books_management_show', function ($breadcrumbs) {
    $breadcrumbs->parent('books_management');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Student Result Templates
Breadcrumbs::register('lms_student_results', function ($breadcrumbs) {
    $breadcrumbs->parent('lms');
    $breadcrumbs->push(trans('LMS::module.student_result.title'), url(config('lms.models.student_result.resource_url')));
});

Breadcrumbs::register('lms_student_result_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_student_results');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('lms_student_result_show', function ($breadcrumbs) {
    $breadcrumbs->parent('lms_student_results');
    $breadcrumbs->push(view()->shared('title_singular'));
});




