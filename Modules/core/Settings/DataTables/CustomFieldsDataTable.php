<?php

namespace Modules\Settings\DataTables;

use Modules\Foundation\DataTables\BaseDataTable;
use Modules\Settings\Models\CustomFieldSetting;
use Modules\Settings\Transformers\CustomFieldSettingTransformer;
use Yajra\DataTables\EloquentDataTable;

class CustomFieldsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('settings.models.setting.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new CustomFieldSettingTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param CustomFieldSetting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CustomFieldSetting $model)
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
            'model' => ['title' => trans('Settings::attributes.custom_field.model')],
            'name' => ['title' => trans('Settings::attributes.custom_field.name')],
            'label' => ['title' => trans('Settings::attributes.custom_field.label')],
            'type' => ['title' => trans('Settings::attributes.custom_field.type')],
            'updated_at' => ['title' => trans('Modules::attributes.updated_at')]
        ];
    }
}
