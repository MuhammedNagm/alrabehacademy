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
use Modules\Components\LMS\Transformers\QuestionTransformer;
use Yajra\DataTables\EloquentDataTable;

class QuestionsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('lms.models.question.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new QuestionTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Question $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Question $model)
    {
     $parent_id = hashids_decode($this->request->get('paragraph'));
     $model = $model->select(['id','title','content','question_type','status','created_at','updated_at']);
     if($parent_id){
            return $model->where('id', $parent_id)->orWhere('parent_id', $parent_id)->orderBy('parent_id', 'asc')->orderBy('id','desc')->newQuery();

         }
        return $model->where('parent_id', null)->orderBy('id','desc')->newQuery();

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            'id'              => ['visible' => false],
            'title'           => ['title' => trans('LMS::attributes.main.name')],
            'content'           => ['title' => trans('LMS::attributes.main.content')],
            // 'slug'            => ['title' => trans('LMS::attributes.main.slug')],
            'question_type'   => ['title' => trans('LMS::attributes.main.type')],
            'correct_answer' => ['title' => trans('LMS::attributes.main.answers'), 
                'searchable'=>false , 'orderable'=>false],
            'status'       =>['title'=> trans('LMS::attributes.main.status')],
            'updated_at' => ['title' => trans('LMS::attributes.main.updated_at')],
        ];
    }

            protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Modules::labels.delete'), 'permission' => 'LMS::question.delete', 'confirmation' => trans('LMS::attributes.confirmation.delete')],
            'add_to_session' => ['title' => trans('LMS::attributes.main.add_to_session'), 'permission' => 'LMS::question.edit', 'confirmation' => trans('LMS::attributes.confirmation.add_to_session')]
        ];
    }

            protected function getOptions()
    {
        
        $url = url(config('lms.models.question.resource_url'));
        return ['resource_url' => $url];
    }
}
