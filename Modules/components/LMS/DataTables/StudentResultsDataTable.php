<?php
/**
 * Created by PhpStorm.
 * User: DevelopNet
 * Date: 7/15/18
 * Time: 8:45 AM
 */

namespace Modules\Components\LMS\DataTables;

use Modules\Foundation\DataTables\BaseDataTable;
use Modules\Components\LMS\Models\StudentResult;
use Modules\Components\LMS\Transformers\StudentResultTransformer;
use Yajra\DataTables\EloquentDataTable;

class StudentResultsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('lms.models.book.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new StudentResultTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param StudentResult $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(StudentResult $model)
    {
        return $model->newQuery();

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'        => ['visible' => false],
            'student_name'     => ['title' => trans('LMS::attributes.student_results.student_name')],
             'status'       =>['title'=> trans('LMS::attributes.main.status')],
             'updated_at' => ['title' => trans('LMS::attributes.main.updated_at')],

        ];
    }
}
