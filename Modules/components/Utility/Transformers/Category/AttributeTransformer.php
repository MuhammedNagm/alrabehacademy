<?php

namespace Modules\Components\Utility\Transformers\Category;

use Modules\Foundation\Transformers\BaseTransformer;
use Modules\Components\Utility\Models\Category\Attribute;

class AttributeTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('utility.models.attribute.resource_url');

        parent::__construct();
    }

    /**
     * @param Attribute $attribute
     * @return array
     * @throws \Throwable
     */
    public function transform(Attribute $attribute)
    {
        return [
            'id' => $attribute->id,
            'type' => $attribute->type,
            'label' => $attribute->label,
            'required' => $attribute->required ? '<i class="fa fa-check text-success"></i>' : '-',
            'use_as_filter' => $attribute->use_as_filter ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($attribute->created_at),
            'updated_at' => format_date($attribute->updated_at),
            'action' => $this->actions($attribute)
        ];
    }
}
