<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditInfoRequest extends FormRequest
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
            'avatar'     => 'image|max:2048|required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required',
            'email'      => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'avatar.image'   => 'Not a image type.',
            'user_image.max' => 'Max size is 2MB.'
        ];
    }
}
