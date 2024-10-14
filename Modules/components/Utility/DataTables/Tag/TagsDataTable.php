<?php

namespace Modules\Components\Utility\DataTables\Tag;

use Modules\Foundation\DataTables\BaseDataTable;
use Modules\Components\Utility\Models\Tag\Tag;
use Modules\Components\Utility\Transformers\Tag\TagTransformer;
use Yajra\DataTables\EloquentDataTable;

class TagsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('utility.models.tag.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TagTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Tag $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Tag $model)
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
            'id' => ['visible' => false],
            'name' => ['title' => trans('Utility::attributes.tag.name')],
            'slug' => ['title' => trans('Utility::attributes.tag.slug')],
            'module' => ['title' => trans('Utility::attributes.tag.module')],
            'status' => ['title' => trans('Modules::attributes.status')],
            'created_at' => ['title' => trans('Modules::attributes.created_at')],
            'updated_at' => ['title' => trans('Modules::attributes.updated_at')],
        ];
    }
}
