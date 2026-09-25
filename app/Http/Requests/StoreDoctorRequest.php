<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:doctors,email'],
            'speciality' => ['required', 'string', 'max:255'],
            'pmdc' => ['required', 'string', 'max:100'],
            'fee' => ['required', 'numeric', 'min:0'],
            'duty_days' => ['nullable', 'array'],
            'duty_time' => ['nullable', 'array'],
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
            'name.required' => 'Doctor name is required.',
            'phone.required' => 'Phone number is required.',
            'email.required' => 'A valid email address is required.',
            'email.unique' => 'A doctor with this email is already registered.',
            'speciality.required' => 'Doctor speciality is required.',
            'pmdc.required' => 'PMDC registration number is required.',
            'fee.required' => 'Doctor consultation fee is required.',
        ];
    }
}
