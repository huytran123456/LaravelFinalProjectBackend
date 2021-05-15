<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
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
            'new_pass'  => 'required',
            'qr_code'   => 'required',
            'hash_mail' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'new_pass.required'  => 'A new_pass is required.',
            'qr_code.required'   => 'A qr_code is required.',
            'hash_mail.required' => 'A hash_mail is required.',
        ];
    }
}
