<?php

namespace Modules\Components\LMS\Http\Requests;

use Modules\Foundation\Http\Requests\BaseRequest;
use Modules\Components\LMS\Models\StudentResult;

class StudentResultRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    public function authorize()
    {
        $this->setModel(StudentResult::class, 'student_results-management');

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(StudentResult::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'student_name' => 'required',

                'status' => 'required',
            ]);
        }

            if ($this->isStore()) {
            $rules = array_merge($rules, [
                // 'slug' => 'required|max:191|unique:lms_student_results,slug'
            ]);
        }

        if ($this->isUpdate()) {
            $student_result = $this->route('student_result');

            $rules = array_merge($rules, [
                // 'slug' => 'required|max:191|unique:lms_student_results,slug,' . $student_result->id,
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
}
