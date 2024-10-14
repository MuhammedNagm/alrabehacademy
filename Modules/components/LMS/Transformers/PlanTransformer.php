<?php

namespace Modules\Components\LMS\Transformers;


use Modules\Foundation\Transformers\BaseTransformer;
use Modules\Components\LMS\Models\Plan;

class PlanTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.plan.resource_url');

        parent::__construct();
    }

 
    /**
     * @param Plan $plan
     * @return array
     * @throws \Throwable
     */
    public function transform(Plan $plan)
    {
        $show_url = url('/packages/' . $plan->hashed_id);
        return [
            'id'           => $plan->id,
            'title'        => '<a href="' . $show_url . '" target="_blank">' . str_limit($plan->title, 50) . '</a>',
            'slug'         => url('/packages/' . $plan->hashed_id),
           
             'price'        => $plan->price,
             'sale_price'        => $plan->sale_price,

            'status'          => formatStatusAsLabels($plan->status > 0?'active': 'inactive'),
            'updated_at'   => format_date($plan->updated_at),
            'action'       => $this->actions($plan)
        ];
    }
}