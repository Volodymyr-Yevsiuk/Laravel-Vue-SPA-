<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric'],
            'image' => ['required', 'image'],
            'description' => ['required', 'string', 'max:1500'],
            'company_id' => ['required', 'exists:App\Models\Company,id']
        ];
    }
}
