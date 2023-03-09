<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
           'title' => 'required|min:3|max:128',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'thumbnail_url' => 'nullable|mimes:jpeg,png,jpg',
            'source_url' => 'nullable|image|mimes:jpeg,png,jpg',
            'demo_url' => 'nullable|image|mimes:jpeg,png,jpg',
            'description' => 'required|min:10'
        ];
    }
}
