<?php

namespace App\Http\Requests;

use App\Rules\SamePassword;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => [
                'bail',
                'required',
                'max:45',
              new SamePassword($this->request)
            ],
            'new_password' => [
                'bail',
                'required',
                'min:6',
            ],
            'new_password_confirmation' => 'bail|required|same:new_password',
        ];
    }
}
