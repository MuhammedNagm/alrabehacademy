<?php
/**
 * Created by PhpStorm.
 * User: DevelopNet
 * Date: 7/15/18
 * Time: 8:45 AM
 */

namespace Modules\Components\LMS\DataTables;

use Modules\Foundation\DataTables\BaseDataTable;
use Modules\Components\LMS\Models\Question;
use Modules\Components\LMS\Models\Quiz;
use Modules\Components\LMS\Transformers\QuizQuestionTransformer;
use Yajra\DataTables\EloquentDataTable;

class QuizQuestionsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('lms.models.quiz_question.resource_url'));


        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new QuizQuestionTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Question $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Question $model)
    {

        $quiz = $this->request->route('quiz');
        if (!$quiz) {
            abort(404);
        }

        $parent_id = hashids_decode($this->request->get('paragraph'));
        if ($parent_id) {
            return $model->where('id', $parent_id)->orWhere('parent_id', $parent_id)->orderBy('parent_id', 'asc')->newQuery();
        }

        \DB::enableQueryLog();

        $getQuestions = $model->select('lms_questions.*', 'lms_quiz_questions.order')
            ->join('lms_quiz_questions', function ($join) use ($quiz) {
                $join->on('lms_questions.id', '=', 'lms_quiz_questions.question_id')
                    ->where('lms_quiz_questions.quiz_id', '=', $quiz->id);
            })
            ->orderBy('lms_quiz_questions.order', 'asc')
            ->newQuery();
//        $getQuestions = $model->where('parent_id', null)->whereHas('quizzes', function ($q) use ($quiz) {
//            $q->where('lms_quizzes.id', $quiz->id);
//        })
//            ->orderBy('lms_quiz_questions.order', 'asc')
//            ->newQuery();
        // dd($getQuestions);
        // $getQuestions = \DB::select(\DB::raw('select `lms_questions`.*, `lms_quiz_questions`.`quiz_id` as `pivot_quiz_id`, `lms_quiz_questions`.`question_id` as `pivot_question_id`, `lms_quiz_questions`.`order` as `pivot_order` from `lms_questions` inner join `lms_quiz_questions` on `lms_questions`.`id` = `lms_quiz_questions`.`question_id` where `lms_quiz_questions`.`quiz_id` = '.$getQuiz->id.' and `parent_id` is null order by `lms_quiz_questions`.`order` asc'));
        // dd($getQuestions);
        // dd(\DB::getQueryLog());
        return $getQuestions;

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            'id' => ['visible' => false],
            'order' => ['title' => trans('LMS::attributes.main.order'),
                'searchable' => false, 'orderable' => false],
            'title' => ['title' => trans('LMS::attributes.main.name')],
            'content' => ['title' => trans('LMS::attributes.main.content')],            // 'slug'            => ['title' => trans('LMS::attributes.main.slug')],
            'question_type' => ['title' => trans('LMS::attributes.main.type')],

            'correct_answer' => ['title' => trans('LMS::attributes.main.answers'),
                'searchable' => false, 'orderable' => false],

            'status' => ['title' => trans('LMS::attributes.main.status')],
            'updated_at' => ['title' => trans('LMS::attributes.main.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Modules::labels.delete'), 'permission' => 'LMS::question.delete', 'confirmation' => trans('Modules::labels.confirmation.title')],
            'add_to_session' => ['title' => trans('LMS::attributes.main.add_to_session'), 'permission' => 'LMS::question.edit', 'confirmation' => trans('LMS::attributes.confirmation.add_to_session')]


        ];
    }

    protected function getOptions()
    {
        $url = url('/lms/quiz-questions');
        return ['resource_url' => $url];


    }
}
