<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'string', 'in:admin,doctor,hr,accountant,receptionist'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:6'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
        ];
    }

    /**
     * Custom error messages for attributes.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'User full name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'A user account with this email already exists.',
            'role.required' => 'Please select a valid hospital role.',
            'role.in' => 'Selected role is not recognized in hospital staff directory.',
            'password.required' => 'Please provide an initial login password for this user.',
            'password.min' => 'Password must be at least 6 characters long.',
        ];
    }
}
