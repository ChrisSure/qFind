<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'required' => 'Field :attribute is require.',
            'email' => 'Enter correct email.',
        ];
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'role' => 'required',
            'status' => 'required',
        ];
    }
}
