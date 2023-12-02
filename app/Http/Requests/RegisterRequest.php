<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:10|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users'
        ];
    }
    //custom validation error messages
    public function messages()
    {
        return [
            'name.unique' => 'Invalid credentials',
            'email.unique' => 'Invalid credentials',
        ];
    }

}
