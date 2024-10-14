<?php

namespace Modules\Components\LMS\Http\Requests;

use Modules\Foundation\Http\Requests\BaseRequest;
use Modules\Components\LMS\Models\Quiz;

class QuizRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Quiz::class);

        return $this->isAuthorized();
    }

    /** 
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Quiz::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'required',
                'content' => 'required',
               // 'status' => 'required',
                // 'price' => 'required',
                'passing_grade' => 'required',
                'retake_count' => 'required',
                // 'author_id' => 'required',
                "create_paragraph.*" => "required_if:create_new_paragraph,==,1"



            ]);
        }

           if($this->get('create_new_paragraph')){
            $rules = array_merge($rules, [
                'create_paragraph.title' => 'required|max:191|unique:lms_questions,title'
            ]);
    
  
            } //endif type paragraph



            if ($this->isStore()) {
            $rules = array_merge($rules, [
               // 'slug' => 'required|max:191|unique:lms_quizzes,slug'
            ]);
        }

        if ($this->isUpdate()) {
            $quiz = $this->route('quiz');

            $rules = array_merge($rules, [
               // 'slug' => 'required|max:191|unique:lms_quizzes,slug,' . $quiz->id,
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

        /**
     * Configure the validator instance.
     *
     * @param Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('sub_quiz.*', 'required', function($input) {

            return $input->sub_quiz['sub_quiz_questions_num'] > 0;
        });

    }
}
