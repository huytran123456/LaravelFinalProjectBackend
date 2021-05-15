<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'price'    => 'required|numeric',
            'duration' => 'required|numeric',
            'film_id'  => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'price.required'    => 'A price is required.',
            'price.numeric'     => 'Price must be numeric.',
            'duration.required' => 'A duration is required.',
            'duration.numeric'  => 'Duration must be numeric.',
            'film_id.numeric'   => 'film_id must be numeric.',
            'film_id.required'  => 'A film_id is required.',

        ];
    }
}
