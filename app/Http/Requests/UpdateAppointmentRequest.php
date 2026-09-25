<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
            'doctor_id' => ['required', 'exists:doctors,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'status' => ['required', 'string', 'in:Pending,Completed,Cancelled,Admitted'],
            'time' => ['required'],
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
            'doctor_id.required' => 'Please select a doctor for the appointment.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'name.required' => 'Patient name is required.',
            'phone.required' => 'Contact phone number is required.',
            'gender.required' => 'Please specify the patient gender.',
            'age.required' => 'Patient age is required.',
            'status.required' => 'Appointment status is required.',
            'time.required' => 'Appointment time is required.',
        ];
    }
}
