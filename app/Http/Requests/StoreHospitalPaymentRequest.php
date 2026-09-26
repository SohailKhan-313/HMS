<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalPaymentRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'in:Consultation,Pharmacy / Medicine,General Procedures,Diagnostics / Lab,Emergency,Other'],
            'patient_id' => ['nullable', 'integer', 'exists:patienthistory,id'],
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_phone' => ['nullable', 'string', 'max:50'],
            'doctor_id' => ['nullable', 'integer', 'exists:doctors,id'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:Cash,Card,Bank Transfer,Wallet'],
            'payment_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:Paid,Partial,Pending,Refunded'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Please select a valid payment category.',
            'category.in' => 'Selected category is not recognized.',
            'patient_name.required' => 'Patient name is required.',
            'amount.required' => 'Payment amount is required.',
            'amount.min' => 'Payment amount must be greater than zero.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_date.required' => 'Payment date is required.',
        ];
    }
}
