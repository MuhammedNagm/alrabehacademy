<?php

namespace Modules\Components\LMS\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Components\LMS\DataTables\StudentResultsDataTable;
use Modules\Components\LMS\Http\Requests\StudentResultRequest;
use Modules\Components\LMS\Models\StudentResult;
use Modules\Components\LMS\Models\Tag;

class StudentResultsController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.student_result.resource_url');
        $this->title = 'LMS::module.student_result.title';
        $this->title_singular = 'LMS::module.student_result.title_singular';

        parent::__construct();
    }

    /**
     * @param StudentResultRequest $request
     * @param StudentResultsDataTable $dataTable
     * @return mixed
     */
    public function index(StudentResultRequest $request, StudentResultsDataTable $dataTable)
    {
        return $dataTable->render('LMS::student_results.index');
    }

    /**
     * @param StudentResultRequest $request
     * @return $this
     */
    public function create(StudentResultRequest $request)
    {
        $student_result = new StudentResult();

        $this->setViewSharedData(['title_singular' => trans('Modules::labels.create_title', ['title' => $this->title_singular])]);

        return view('LMS::student_results.create_edit')->with(compact('student_result'));
    }

    /**
     * @param StudentResultRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StudentResultRequest $request)
    {
        try {

            $data = $request->except(['thumbnail', 'categories', 'file_clear']);

            // $data['author_id'] = user()->id;

            $student_result = StudentResult::create($data);

           if ($request->hasFile('thumbnail')) {
                $student_result->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($student_result->mediaCollectionName);
                   }



            $student_result->categories()->sync($request->input('categories', []));




            flash(trans('Modules::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, StudentResult::class, 'created');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param StudentResultRequest $request
     * @param StudentResult $student_result
     * @return $this
     */
    public function show(StudentResultRequest $request, $hashed_id)
    {
        $id = hashids_decode($hashed_id);
        $student_result = StudentResult::find($id);

        return view('LMS::student_results.show')->with(compact('student_result'));
    }


    /**
     * @param StudentResultRequest $request
     * @param StudentResult $student_result
     * @return $this
     */
    public function edit(StudentResultRequest $request, StudentResult $student_result)
    {

        $this->setViewSharedData(['title_singular' => trans('Modules::labels.update_title', ['title' => $student_result->student_name])]);

        return view('LMS::student_results.create_edit')->with(compact('student_result'));
    }

    /**
     * @param StudentResultRequest $request
     * @param StudentResult $student_result
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(StudentResultRequest $request, StudentResult $student_result)
    {
        try {

          $data = $request->except(['thumbnail', 'clear','categories']);

            // $data['author_id'] = user()->id;
            $student_result->update($data);

            if ($request->has('clear') || $request->hasFile('thumbnail')) {
                $student_result->clearMediaCollection($student_result->mediaCollectionName);
            }


           if ($request->hasFile('thumbnail')) {
                $student_result->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($student_result->mediaCollectionName);
                   }


            $student_result->categories()->sync($request->input('categories', []));


            flash(trans('Modules::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, StudentResult::class, 'update');
        }

        return redirectTo($this->resource_url);
    }




    /**
     * @param StudentResultRequest $request
     * @param StudentResult $student_result
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(StudentResultRequest $request, StudentResult $student_result)
    {
        try {
            $student_result->clearMediaCollection('thumbnail');
            $student_result->delete();

            $message = ['level' => 'success', 'message' => trans('Modules::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, StudentResult::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}