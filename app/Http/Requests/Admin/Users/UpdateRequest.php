<?php

namespace App\Http\Requests\Admin\Users;

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
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email,'. $this->request->get('user_id'),
            'mobile' => 'required|digits:11|unique:users,mobile,'. $this->request->get('user_id'),
            'role' => 'required|in:admin,user,seller'
        ];
    }
}
