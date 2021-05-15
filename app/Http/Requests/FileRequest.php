<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'user_image' => 'image|max:2048|mimes:jpeg,png|mimetypes:image/jpeg,image/png',
        ];
    }

    public function messages()
    {
        return [
            'user_image.image' => 'Not a image type.',
            'user_image.max'   => 'Max size is 2MB.'
        ];
    }
}
