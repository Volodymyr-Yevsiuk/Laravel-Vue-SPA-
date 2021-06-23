<?php

namespace App\Http\Requests\Company;

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
            'name' => ['required', 'max:150'],
            'image' => ['required', 'image'],
            'employees' => ['required', 'numeric'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string', 'max:1500'],
            'user_id' => ['required', 'exists:App\Models\User,id']
        ];
    }
}
