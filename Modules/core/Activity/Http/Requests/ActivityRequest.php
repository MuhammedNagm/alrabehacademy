<?php

namespace Modules\Activity\Http\Requests;

use Modules\Activity\Models\Activity;
use Modules\Foundation\Http\Requests\BaseRequest;

class ActivityRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Activity::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Activity::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
        }

        return $rules;
    }
}
