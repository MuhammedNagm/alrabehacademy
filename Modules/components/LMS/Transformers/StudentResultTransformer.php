<?php
/**
 * Created by PhpStorm.
 * User: DevelopNet
 * Date: 7/15/18
 * Time: 9:58 AM
 */

namespace Modules\Components\LMS\Transformers;

use Modules\Foundation\Transformers\BaseTransformer;
use Modules\Components\LMS\Models\StudentResult;

class StudentResultTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.student_result.resource_url');

        parent::__construct();
    }

    /**
     * @param StudentResult $student_result
     * @return array
     * @throws \Throwable
     */
    public function transform(StudentResult $student_result)
    {
        return [
            'id'      => $student_result->id,
            'student_name'   => $student_result->student_name,
            'status'   => formatStatusAsLabels($student_result->status > 0?'active': 'inactive'),
            'updated_at' => format_date($student_result->updated_at),
            'action' => $this->actions($student_result)

        ];
    }
}