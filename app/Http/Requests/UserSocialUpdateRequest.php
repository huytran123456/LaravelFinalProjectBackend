<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSocialUpdateRequest extends FormRequest
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
        $rules = [
            //
            'password' => 'required',
            'email'    => 'required|email',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required'    => 'An email is required.',
            'email.email'       => 'An email is invalid.',
            'password.required' => 'A password is required.',
        ];
    }
}
