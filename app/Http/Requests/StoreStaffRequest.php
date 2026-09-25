<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:staff,email'],
            'phone' => ['required', 'string', 'max:20'],
            'designation' => ['nullable', 'string', 'max:100'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Staff member name is required.',
            'email.required' => 'A valid email address is required.',
            'email.unique' => 'A staff member with this email is already registered.',
            'phone.required' => 'Phone number is required.',
            'salary.numeric' => 'Salary must be a valid number.',
            'image.image' => 'Uploaded file must be a valid image.',
            'image.max' => 'Staff image may not be larger than 2MB.',
        ];
    }
}
