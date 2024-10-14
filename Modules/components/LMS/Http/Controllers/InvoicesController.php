<?php

namespace Modules\Components\LMS\Http\Controllers;

use Modules\Foundation\Http\Controllers\BaseController;
use Modules\Components\LMS\DataTables\InvoicesDataTable;
use Modules\Components\LMS\Http\Requests\InvoiceRequest;
use Modules\Components\LMS\Models\Invoice;
use Modules\Components\LMS\Models\Quiz;
use Illuminate\Http\Request;

class InvoicesController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.invoice.resource_url');
        $this->title = 'LMS::module.invoice.title';
        $this->title_singular = 'LMS::module.invoice.title_singular';

        parent::__construct();
    }

    /**
     * @param InvoiceRequest $request
     * @param InvoicesDataTable $dataTable
     * @return mixed
     */
    public function index(InvoiceRequest $request, InvoicesDataTable $dataTable)
    {
        return $dataTable->render('LMS::invoices.index');
    }

    /**
     * @param InvoiceRequest $request
     * @return $this
     */
    public function create(InvoiceRequest $request)
    {
        $invoice = new Invoice();

        $session_id = \LMS::codeGenerator(5, true ,'invoice_',user()->hashed_id);



        $this->setViewSharedData(['title_singular' => trans('Modules::labels.create_title', ['title' => $this->title_singular])]);

        return view('LMS::invoices.create_edit')->with(compact('invoice', 'session_id'));
    }


        /**
     * @param InvoiceRequest $request
     * @return $this
     */
    public function change_status(InvoiceRequest $request, Invoice $invoice)
    {

        $this->setViewSharedData(['title_singular' => trans('Modules::labels.create_title', ['title' => $this->title_singular])]);

        return view('LMS::invoices.partials.change_status')->with(compact('invoice'));
    }

    /**
     * @param InvoiceRequest $request
     * @return $this
     */
    public function update_status(Request $request, $hashed_id)
    {
         if (!user()->hasPermissionTo('LMS::invoice.update')) {
            abort(404);
        }

        $this->validate($request, ['status' => 'required']);

        try {
        $data = $request->all();
        $id = hashids_decode($hashed_id);

        $invoice = Invoice::find($id);

        $invoice->update([
                'status' => $data['status'],
               
            ]);

        $invoiceItems = $invoice->invoicables()->get();
if($invoiceItems){
   foreach ($invoiceItems as $item) {
    if($data['status'] == 'paid'){
            $paid = $item->price;
        }else{
            $paid = 0.00;
        }
      $item->update([
        'paid' => $paid,
          
            ]);
   }

   }

        $invoice->subscriptions()->update([
            'status' => ($data['status'] == 'paid')?1:0,
        ]);


     flash(trans('Modules::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Order::class, 'update');
        }

                return redirectTo($this->resource_url);

    }

    /**
     * @param InvoiceRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(InvoiceRequest $request)
    {
        try {

            $data = $request->except(['thumbnail', 'plan', 'course', 'quiz']);
            $invoice = Invoice::create($data);
            if ($request->hasFile('thumbnail')) {
                $course->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($invoice->mediaCollectionName);
                   }

            $invoice->categories()->sync($request->input('categories', []));
            $invoice->courses()->sync($request->input('courses', []));
            $invoice->quizzes()->sync($request->input('quizzes', []));

            $tags = $this->getTags($request);

            $invoice->tags()->sync($tags);


            flash(trans('Modules::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'created');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return $this
     */
    public function show(InvoiceRequest $request, Invoice $invoice)
    {
        return redirect('admin-preview/' . $invoice->slug);
    }


    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return $this
     */
    public function edit(InvoiceRequest $request, Invoice $invoice)
    {
        $this->setViewSharedData(['title_singular' => trans('Modules::labels.update_title', ['title' => $invoice->title])]);

        return view('LMS::invoices.create_edit')->with(compact('invoice'));
    }

    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        try {
        $data = $request->except(['thumbnail', 'plan', 'course', 'quiz', 'clear']);

            // $data['author_id'] = user()->id;
            $invoice->update($data);

           if ($request->has('clear') || $request->hasFile('thumbnail')) {
                $invoice->clearMediaCollection('thumbnail');
            }

           if ($request->hasFile('thumbnail')) {
                $invoice->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($invoice->mediaCollectionName);
                   }

           $invoice->categories()->sync($request->input('categories', []));
            $invoice->courses()->sync($request->input('courses', []));
            $invoice->quizzes()->sync($request->input('quizzes', []));

            $tags = $this->getTags($request);

            $invoice->tags()->sync($tags);

            flash(trans('Modules::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'update');
        }

        return redirectTo($this->resource_url);
    }


      private function getTags($request)
    {
        $tags = [];

        $requestTags = $request->get('tags', []);

        foreach ($requestTags as $tag) {
            if (is_numeric($tag)) {
                array_push($tags, $tag);
            } else {
                try {
                    $newTag = Tag::create([
                        'name' => $tag,
                        'slug' => str_slug($tag)
                    ]);

                    array_push($tags, $newTag->id);
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }

        return $tags;
    }


    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(InvoiceRequest $request, Invoice $invoice)
    {
        try {
            $invoice->clearMediaCollection('featured-image');
            $invoice->delete();

            $message = ['level' => 'success', 'message' => trans('Modules::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function create_quizzes()
    {

        $quizzes = Quiz::where('parent_id',null)->where('duration','<',1)->where('is_sub_quiz','!=',1)->has('children', '=', 0)->take(30)->get();
        if(!$quizzes->count()){
            dd(false);
        }
        if($quizzes->count()){
          foreach ($quizzes as $quiz) {

        $arr_questions_ids = $quiz->questions()->whereNotNull('lms_questions.id')->orderBy('parent_id', 'asc')->orderBy('lms_quiz_questions.order', 'asc')->pluck('lms_questions.id')->toArray();
        $files = $quiz->files()->whereNotNull('lms_embeded_media.id')->get();
$quiz_questions_count = $quiz->questions()->count();
        $newQuiz = $quiz->replicate();
        $newQuiz->title = $quiz->title.  '(اختبر نفسك)';
        $newQuiz->slug = $quiz->slug.'sub_quiz'. uniqid();
        $newQuiz->parent_id = $quiz->id;
        $newQuiz->is_sub_quiz = true;
        $newQuiz->duration = $quiz_questions_count;
        $newQuiz->duration_unit = 'minute';
        $newQuiz->retake_count = 100000;
        $newQuiz->sub_quiz_questions_num = 10000;
        $newQuiz->sub_quiz_random = 0;
        $newQuiz->private = 1;
        $newQuiz->save();

            $q_ids = [];
            $orders = [];

            foreach ($arr_questions_ids as $index => $id) { 
                $q_ids[] = $id; 
                $orders[] = ['order' => $index]; 
            } 
            $newQuiz->questions()->sync(array_combine($q_ids, $orders));



        } 

               # code...
            }


dd(true);

    }
}