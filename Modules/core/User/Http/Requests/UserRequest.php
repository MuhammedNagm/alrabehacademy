<?php

namespace Modules\User\Http\Requests;

use Modules\Foundation\Http\Requests\BaseRequest;
use Modules\User\Models\User;

class UserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('user');

        if ($this->isEdit() && !is_null($this->model)) {
            if (!isSuperUser() && isSuperUser($this->model) && $this->model->id != user()->id) {
                return false;
            }
        }

        $this->model = is_null($this->model) ? User::class : $this->model;

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(User::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:191',
                    'roles' => 'required',
                    'picture' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
                    // 'phone_country_code' => 'required',
                ]
            );
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'email' => 'required|email|max:191|unique:users,email',
                    // 'phone_number' => 'required|unique:users',
                    'password' => 'required|confirmed|max:191|min:6'
                ]
            );
        }

        if ($this->isUpdate()) {
            $user = $this->route('user');

            $rules = array_merge($rules, [
                    'email' => 'required|email|max:191|unique:users,email,' . $user->id,
                    // 'phone_number' => 'required|unique:users,phone_number,' . $user->id,
                    'password' => 'nullable|confirmed|max:191|min:6'
                ]
            );
        }
        return $rules;
    }
}
