<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Product\UpdateRequest;

class StoreRequest extends UpdateRequest
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
        $rules = parent::rules();

        $rules['image'] = ['image'];
        $rules['company_id'] = ['required', 'exists:App\Models\Company,id'];
        
        return $rules;
    }
}
