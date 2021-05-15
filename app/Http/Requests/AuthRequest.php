<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            //
            'email'       => 'required|email',
            'password'    => 'required',
            'remember_me' => 'boolean|required'
        ];
    }

    /**
     * Custom message
     * @return string[]
     */
    public function messages()
    {
        return [
            'email.required'       => 'An email is required.',
            'email.email'          => 'An email is invalid.',
            'password.required'    => 'A password is required.',
            'remember_me.boolean'  => 'Must be boolean type.',
            'remember_me.required' => 'Remember me is required.'
        ];
    }
}
