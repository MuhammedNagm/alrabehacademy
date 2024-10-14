<?php
/**
 * Created by PhpStorm.
 * User: DevelopNet
 * Date: 7/15/18
 * Time: 9:58 AM
 */

namespace Modules\Components\LMS\Transformers;

use Modules\Foundation\Transformers\BaseTransformer;
use Modules\Components\LMS\Models\Question;
use Illuminate\Support\Str;

class QuestionTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('lms.models.question.resource_url');

        parent::__construct();
    }

    /**
     * @param Question $question
     * @return array
     * @throws \Throwable
     */
    public function transform(Question $question)
    {
        
        $actions = ['edit'=> [ 
            'href' => url($this->resource_url . '/' . $question->hashed_id . '/edit'),
                    'label' => trans('Modules::labels.edit'),
                    'data' => []
                ]
        ];

        if (user()->hasPermissionTo('LMS::question.delete')) {
            $actions['delete'] = [
                'icon'  => 'fa fa-fw',
                    'href' => url($this->resource_url . '/' . $question->hashed_id),
                    'label' => trans('Modules::labels.delete'),
                    'data' => [
                        'action' => 'delete',
                        'table' => '.dataTableBuilder'
                    ]

            ];
        }

        if($question->question_type == 'paragraph' && \Request::get('paragraph') != $question->hashed_id){
            $actions['questions'] =           [
           'href' => url($this->resource_url . '?paragraph='.$question->hashed_id),
           'label' => __('LMS::attributes.main.questions'),
           'data' => []
       ];

        }

        return [
            'id' => $question->id,
            'checkbox' => $this->generateCheckboxElement($question),
            'title' => $question->title,

            'content' => Str::limit(strip_tags($question->content), 100, '...'),
            // 'slug' => $question->slug,
            'question_type' => __('LMS::attributes.questions.'.$question->question_type),
            'correct_answer' => formatArrayAsLabels($question->answers->where('is_correct','=','1')->pluck('title')),
            'status' => formatStatusAsLabels($question->status > 0?'active': 'inactive'),
            'updated_at' => format_date($question->updated_at),
            'action' => $this->actions($question, $actions, null, false)
        ];
    }


}