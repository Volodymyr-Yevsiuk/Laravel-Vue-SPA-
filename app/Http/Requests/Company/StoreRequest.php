<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Company\UpdateRequest;

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
        $rules['user_id'] = ['required', 'exists:App\Models\User,id'];

        return $rules;
    }
}
