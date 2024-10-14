<?php


Route::resource('manage/books', 'BooksController');
Route::get('manage/books/{id}/show', 'BooksController@show')->name('bookings.show');
Route::group(['prefix' => 'lms'], function () {

    Route::get('plans', 'LMSController@realTimeVisitors');
    Route::get('active-users', 'LMSController@realTimeVisitors');
    Route::resource('categories', 'CategoriesController', ['except' => ['show']]);
    Route::resource('courses', 'CoursesController');
    Route::resource('student_results', 'StudentResultsController');
    Route::resource('lessons', 'LessonsController');
    Route::resource('testimonials', 'TestimonialsController');
    Route::resource('certificates', 'CertificatesController');
    Route::get('certificates/{id}/show', 'CertificatesController@show')->name('lms.certificates.show');
    Route::resource('questions', 'QuestionsController');
    Route::resource('quizzes.questions', 'QuizQuestionsController');
    Route::resource('quizzes', 'QuizzesController', ['except' => ['show']]);
    Route::resource('plans', 'PlansController', ['except' => ['show']]);
    Route::resource('tags', 'TagsController', ['except' => ['show']]);
    Route::resource('invoices', 'InvoicesController', ['except' => ['show']]);
    Route::resource('coupons_groups', 'CouponsGroupsController');
    Route::get('coupons_groups/{group}/coupons-list', 'CouponsGroupsController@coupons_list')->name('coupons_list');
    Route::resource('coupons', 'CouponsController');
    Route::resource('subscriptions', 'SubscriptionsController', ['except' => ['show']]);
    Route::get('subscriptions/{subscription}/change_status', 'SubscriptionsController@change_status')->name('subscriptions.change_status');
    Route::put('subscriptions/{subscription}/change_status', 'SubscriptionsController@update_status')->name('subscriptions.update_status');
    Route::get('invoices/{invoice}/change_status', 'InvoicesController@change_status')->name('invoices.change_status');
    Route::put('invoices/{invoice}/change_status', 'InvoicesController@update_status')->name('invoices.update_status');
    
    Route::get('quizzes/show_quizzes_select2_list', 'QuizzesController@show_quizzes_select2_list');
    Route::post('quizzes/session_questions_to_quiz', 'QuizzesController@session_questions_to_quiz');

         Route::get('quizzes/{quiz}/clone', 'QuizzesController@clone_quiz');


     Route::get('quizzes/{quiz}/delete-options', 'QuizzesController@delete_options');
        Route::post('quizzes/{quiz}/delete-quiz', 'QuizzesController@delete_quiz');

        Route::post('questions/bulk-action', 'QuestionsController@bulkAction');
        Route::post('quiz-questions/bulk-action', 'QuizQuestionsController@bulkAction')->name('quiz-questions-actions');





    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {

        Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {

            Route::post('/{item}/{item_id}/remove_invoice_item/{session_id}', 'AjaxController@remove_invoice_item')->name('remove_item');
            Route::get('/{type}/items_list/{session_id}', 'AjaxController@invoice_items_list')->name('items_list');
            Route::post('/{type}/{item_id}/{session}/add_to_invoice', 'AjaxController@invoice_add_new_item')->name('add_new_item');

        });


        Route::group(['prefix' => 'questions', 'as' => 'questions.'], function () {

            Route::get('create/{session_id}', 'AjaxController@create_question')->name('create');

            Route::get('/{question_id}/{session_id}/edit', 'AjaxController@edit_question')->name('edit');
            Route::post('store', 'AjaxController@store_question')->name('store');
            Route::put('/{question_id}/update', 'AjaxController@update_question')->name('update');
            Route::get('{session_id}/list', 'AjaxController@list_questions')->name('list');
            Route::get('{question_id}/{session_id}/add_to_quiz', 'AjaxController@add_to_quiz')->name('add_to_quiz');
            Route::get('{question_id}/{session_id}/remove_from_quiz', 'AjaxController@remove_from_quiz')->name('remove_from_quiz');
            Route::get('title-search', 'AjaxController@searchQuestionsTitles')->name('title-search');
        });

        Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {



            Route::post('{section_id}/{item}/{session_id}/create', 'AjaxController@create_course_item')->name('create_item');
            Route::get('/{item}/{course_id}/edit', 'AjaxController@edit_course_item')->name('edit');
            Route::post('/{item}/store', 'AjaxController@store_course_item')->name('store');

            Route::put('/{item_id}/{item}/update', 'AjaxController@update_course_item')->name('item_update');

            Route::post('/{item}/{item_id}/{session_id}/remove_from_course', 'AjaxController@remove_course_item')->name('item_remove');

            Route::get('/section/{section_id}/edit_section', 'AjaxController@show_edit_section')->name('edit_section');
            Route::get('/{section_id}/{item_type}/{session_id}/list', 'AjaxController@list_course_items')->name('list');
            Route::post('{section_id}/{item}/{item_id}/{session_id}/add_to_section', 'AjaxController@add_to_section')->name('add_to_course');

        });

    });

});


// Start frontend routes

Route::group([
    'namespace' => "Frontend"
], function () {

    Route::get('ajax/{module}/{module_id}/favourite', 'FavouritesController@favourite')->name('ajax.favourite');
//books
    Route::group(['prefix' => 'books', 'as' => 'books.'], function () {

        Route::get('/', 'BooksController@index')->name('index');
        Route::get('/{book}/show', 'BooksController@show')->name('show');
        Route::get('/{book}/preview', 'BooksController@preview')->name('preview');
    });

//Courses
    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {


        Route::post('{course_id}/retake', 'CoursesController@retake_course')->name('retake');


        Route::get('/', 'CoursesController@index')->name('index');
        Route::get('/{id}/show', 'CoursesController@show')->name('show');
        Route::get('/{course_id}/live', 'CoursesController@live_class')->name('live_class');

        //Lesson mark as Completed
        Route::put('{course_id}/completed', 'CoursesController@markCourseAsCompleted')->name('course_completed');

        //Lesson mark as Completed
        Route::put('{course_id}/lesson/{lesson_id}/completed', 'CoursesController@lesson_completed')->name('lesson_completed');

        //Lessons
        Route::get('{course_id}/lesson/{lesson_id}/show', 'CoursesController@showLesson')->name('lesson');

        //exam
        Route::get('{course_id}/results', 'CoursesController@showCourseResults')->name('results')->middleware(['auth']);
        Route::get('{course_id}/quiz/{quiz_id}/show', 'QuizzesController@showCourseQuiz')->name('quiz');
        Route::get('/quiz-result', 'LessonsController@QuizResult')->name('quiz-result');

    });

    //Categories
    Route::get('/categories', 'CategoriesController@index')->name('categories.index');
    Route::get('/categories/{id}', 'CategoriesController@show')->name('categories.show');
    // Route::get('c/{slug}', 'CategoriesController@show')->name('category.show');

//quizzespage
    Route::group(['prefix' => 'quizzes', 'as' => 'quizzes.'], function () {

         Route::get('{quiz}/lood_in_ifram', 'QuizzesController@loadInIfram')->name('lood_in_ifram');


        Route::post('/ajax/{log_id}/page/{page}/stop_here', 'QuizzesController@stop_here_page')->name('stop_here_page');

        Route::get('/ajax/{quiz_id}/logs/{log_id}/get_quiz_progress', 'QuizzesController@ajax_get_quiz_progress')->name('ajax_get_quiz_progress');



        Route::get('/ajax/ask_teacher/quizzes/{quiz_id}/questions/{question_id}', 'QuizzesController@getAskTeacherModal')->name('open_modal_ask_teacher');


        Route::get('/ajax/{quiz_id}/u/{logs_id}/q/{question_id}/mark_as_delayed', 'QuizzesController@markAsDelayed')->name('markAsDelayed');


        Route::post('/ajax/quizzes/{quiz_id}/u/{log_id}/q/{question_id}/check_answers', 'QuizzesController@check_answers')->name('check_answers');

        Route::post('/ajax/quizzes/{quiz_id}/u/{log_id}/show_results', 'QuizzesController@show_result')->name('show_result');

        Route::post('/ajax/quizzes/{quiz_id}/u/{log_id}/question_id/{question_id}/preview', 'QuizzesController@previewQuizLogs')->name('question_answers.preview');


        Route::post('/ajax/{quiz}/enroll/{course?}', 'QuizzesController@enrollQuiz')->name('enroll_quiz');
        Route::post('/ajax/{quiz_id}/{logs_id}/answer', 'QuizzesController@answerQuestions')->name('answer_questions');
        Route::post('/ajax/{quiz_id}/questions/{question_id}/answer', 'QuizzesController@answerQuestion')->name('answer_question');
        Route::get('/', 'QuizzesController@index')->name('index');
        Route::get('/{id}/show', 'QuizzesController@show')->name('show');

        Route::group(['middleware' => ['auth', 'web']], function () {
                    Route::get('/{quiz_id}/questions/{question_id}/preview', 'QuizzesController@previewQuestion')->name('question_preview');

        Route::get('/{quiz_id}/u/{logs_id}/q/favourite-questions/{course?}', 'QuizzesController@favourite_questions')->name('favourite_questions');

        Route::get('/{quiz_id}/{log_id}/get_questions', 'QuizzesController@getQuestions')->name('get_questions');

        Route::post('/{quiz_id}/retakeQuiz', 'QuizzesController@retakeQuiz')->name('retakeQuiz');

            Route::get('/{id}/questions', 'QuizzesController@show_questions')->name('show_questions');
            Route::get('{quiz_id}/embeded/{course_id?}', 'QuizzesController@loadEmbededQuiz')->name('embeded');
            Route::get('{quiz_log_id}/questions/{question_id}/handel_enroll_question', 'QuizzesController@handelEnrollQuestion')->name('handel_enroll_question');
            Route::get('/{quiz}/handel_quiz', 'QuizzesController@handel_quiz')->name('handel_quiz');
            Route::get('/{quiz}/quiz-page/{course?}', 'QuizzesController@quizPage')->name('quizPage');
            Route::get('/{quiz}/delayed/u/{quiz_logs}', 'QuizzesController@delayedQuestions')->name('delayed_questions');
            Route::get('/quiz', 'QuizzesController@quiz')->name('quiz');
            Route::get('/quiz-result', 'QuizzesController@QuizResult')->name('quiz-result');
        });


    });

    //exercises

    Route::group(['prefix' => 'exercises', 'as' => 'exercises.'], function () {

        Route::get('/', 'ExercisesController@index')->name('index');

        Route::get('/{id}/show', 'ExercisesController@show')->name('show');
        Route::get('/{quiz_id}/show_questions', 'ExercisesController@show')->name('show_questions');


    });


//Blogs
    Route::group(['prefix' => 'blogs', 'as' => 'blogs.'], function () {
        Route::get('/', 'BlogsController@index')->name('index');
        Route::get('/show', 'BlogsController@show')->name('show');
    });

//Account
    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('/{user_id}', 'AccountController@profile')->name('profile');
        Route::put('{user_id}/update', 'AccountController@update')->name('update');


    });

// Route::get('/login', 'AccountController@login')->name('login');
// Route::get('/register', 'AccountController@register')->name('register');


    //Bouquets || Plans

    Route::group(['prefix' => 'packages', 'as' => 'plans.'], function () {
        Route::get('/{plan}/search', 'PlansController@search_in_plan')->name('search_in_plan');
        Route::get('/', 'PlansController@index')->name('index');
        Route::get('/{plan_id}', 'PlansController@show')->name('show');
        Route::get('/{plan}/content', 'PlansController@plan_content')->name('plan_content');
        Route::get('/{plan}/categories/{category}', 'PlansController@category')->name('category');
        Route::group(['prefix' => 'quizzes', 'as' => 'quizzes.'], function () {
        Route::get('{plan}/quizzes/{quiz}/show', 'PlansController@quiz_show')->name('show');
        });
    });

     //subscription
    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::post('{module}/{module_id}/subscribe', 'SubscriptionsController@subscribe')->name('subscribe');
        // Route::get('{module}/{module_id}/subscribe', 'SubscriptionsController@subscriptionPage')->name('subscribe_page');
        Route::get('{module}/{module_id}/create', 'SubscriptionsController@subscriptionPage')->name('create');
        Route::post('{module}/{module_id}/store', 'SubscriptionsController@store_subscription')->name('store');
        Route::get('ajax/user/{user_id}/invoice/{invoice}/ajax_show', 'BookingController@ajax_show_invoice')->name('ajax_invoice');
        Route::get('ajax/{status}/change_invoice_status', 'SubscriptionsController@ajax_change_invoice_status')->name('change_invoice_status.edit');
        Route::post('ajax/{status}/change_invoice_status', 'SubscriptionsController@ajax_update_invoice_status')->name('change_invoice_status.update');

        Route::get('{user_id}/invoices', 'BookingController@invoices')->name('invoices');
        Route::get('/pricing-tables', 'BookingController@pricingTables')->name('pricing-tables');

        Route::get('ajax/{invoice}/get-pay-code-form', 'SubscriptionsController@get_pay_code_form')->name('get_pay_code_form');
        Route::post('{invoice}/submit', 'SubscriptionsController@submit_pay_code')->name('submit_pay_code');

    });


    Route::get('ajax/certificates/{id}/show', 'AccountController@ajax_show_certificate')->name('ajax.get_certificate');


    Route::get('search', 'HomeController@search')->name('Home.search');

    Route::get('/home', 'HomeController@index');

//chat

       Route::get('/ajax/load-board', 'QuizzesController@load_board');
       Route::post('/ajax/save-board-db', 'QuizzesController@save_board_in_database');
       Route::post('/ajax/save-board-sen', 'QuizzesController@save_board_in_session');
       Route::get('/ajax/preview-embeded-video', 'QuizzesController@get_embedded_video');




    Route::group(['prefix' => ''], function () {
        Route::get('messages', 'MessangerController@index')->name('message.index');
        Route::get('message/{id}', 'MessangerController@chatHistory')->name('message.read');
        Route::post('ajax/store-audio-message', 'MessangerController@storeAudioMessage')->name('message.audio_store');

        Route::group(['prefix' => 'ajax', 'as' => 'ajax::'], function () {
            Route::post('ask_teacher/send', 'MessangerController@ajaxAskTeacher')->name('message.ask_teacher');
            Route::post('message/send', 'MessangerController@ajaxSendMessage')->name('message.new');
            Route::delete('message/delete/{id}', 'MessangerController@ajaxDeleteMessage')->name('message.delete');
        });

    });

 Route::get('/ajax/student-results/{student}/show', 'HomeController@ajax_show_student_result')->name('student_result.ajax_load');


});
















