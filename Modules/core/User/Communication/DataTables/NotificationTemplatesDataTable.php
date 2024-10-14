<?php

namespace Modules\User\Communication\DataTables;

use Modules\Foundation\DataTables\BaseDataTable;
use Modules\User\Communication\Models\NotificationTemplate;
use Modules\User\Communication\Transformers\NotificationTemplateTransformer;
use Yajra\DataTables\EloquentDataTable;

class NotificationTemplatesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('notification.models.notification_template.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new NotificationTemplateTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param NotificationTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NotificationTemplate $model)
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
            'friendly_name' => ['title' => trans('Notification::attributes.notification_template.friendly_name')],
            'name' => ['title' => trans('Notification::attributes.notification_template.name')],
            'title' => ['title' => trans('Notification::attributes.notification_template.title')],
            'created_at' => ['title' => trans('Modules::attributes.created_at')],
            'updated_at' => ['title' => trans('Modules::attributes.updated_at')],
        ];
    }

    protected function getFilters()
    {
        return [
            'name' => ['title' => trans('Notification::attributes.notification_template.name'), 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'title' => ['title' => trans('Notification::attributes.notification_template.title'), 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
        ];
    }
}
