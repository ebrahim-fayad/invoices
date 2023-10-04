<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_name'=>'required|unique:products',
            'section_id'=>'required',
            'description'=>'required',
        ];
    }
    public function messages(): array
    {
        return [
            'product_name.required'=>'ماينفعش نسيبها فاضية',
            'product_name.unique'=>'ما ينفعش تتكرر',
            'section_id.required'=>'ماينفعش نسيبها فاضية',
            'description.required'=>'ماينفعش نسيبها فاضية',
        ];
    }
}
