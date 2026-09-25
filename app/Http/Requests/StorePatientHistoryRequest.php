<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientHistoryRequest extends FormRequest
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
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
            'cnic' => ['nullable', 'string', 'max:20'],
            'due_amount' => ['nullable', 'numeric', 'min:0'],
            'wallet_amount' => ['nullable', 'numeric', 'min:0'],
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
            'name.required' => 'Patient name is required.',
            'age.required' => 'Patient age is required.',
            'phone.required' => 'Patient phone number is required.',
            'due_amount.numeric' => 'Due amount must be a valid number.',
            'wallet_amount.numeric' => 'Wallet amount must be a valid number.',
        ];
    }
}
