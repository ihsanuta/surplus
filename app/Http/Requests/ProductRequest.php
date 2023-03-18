<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'required|exists:category,id',
            'image_id' => 'required|exists:image,id',
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'description' => 'required|string',
            'enable' => 'boolean'
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array<string, string>
    */
    public function messages(): array
    {
        return [
            'category_id.required' => 'category id is required',
            'category_id.exists' => 'category id must exists',
            'image_id.required' => 'image id is required',
            'image_id.exists' => 'image id must exists',
            'name.required' => 'name is required',
            'name.regex' => 'name must alphabet',
            'description.required' => 'description is required',
            'description.required' => 'description must string',
            'enable.boolean' => 'enable must boolean'
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status'   => 'error',
            'message'   => 'Validation errors',
            'error'      => $validator->errors()
        ], 400));
    }
}
