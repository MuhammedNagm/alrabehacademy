<?php

Route::get('/', '\Modules\Foundation\Http\Controllers\PublicBaseController@welcome');



Route::get('/replace', function(){

	$class = new Nette\PhpGenerator\ClassType('Demo');

	$class
	->setFinal()
	->setExtends('ParentClass')
	->addImplement('Countable')
	->addTrait('Nette\SmartObject')
	->addComment("Description of class.\nSecond line\n")
	->addComment('@property-read Nette\Forms\Form $form');
$my_file = base_path('app/new.php');
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


$oldMessage = '@replaceThis';

$deletedFormat = 'function show($id){


	$this->hello;
} ';

$searchReplaceArray = [$oldMessage => $deletedFormat, 'caegory'=> 'post'];

//read the entire string
$str=file_get_contents(base_path('app/test.php'));

//replace something in the file string - this is a VERY simple example
$str=str_replace(array_keys($searchReplaceArray), array_values($searchReplaceArray),$str);

//write the entire string
file_put_contents($my_file, $class);



	return redirect()->to('/');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/privet_quizzes', function(){
// 	$quizzes = \DB::table('lms_quizzes')->whereNotNull('id');

// 	if($quizzes->count()){

// 		$quizzes->update(['show_in_plan' => 1]);

// 	}

// });

// Route::get('/privet_categories', function(){
// 	$quizzes = \DB::table('lms_categories')->whereNotNull('id');

// 	if($quizzes->count()){

// 		$quizzes->update(['show_in_plan' => 1]);

// 	}

// });
