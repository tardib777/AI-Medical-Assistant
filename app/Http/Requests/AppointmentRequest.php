<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null && method_exists($user, 'hasRole') && $user->hasRole('Doctor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_time' => [
                'required',
                 Rule::date()->format('Y-m-d H:i:s'),
                'after:now',
            ],

            'appointment_location' => [
                'required',
                'string',
                'max:255',
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'appointment_time.required' => 'The appointment time is required.',
            'appointment_time.date' => 'The appointment time must be a valid date.',
            'appointment_time.after' => 'The appointment time must be a future date and time.',
            'appointment_location.required' => 'The appointment location is required.',
            'appointment_location.string' => 'The appointment location must be a string.',
            'appointment_location.max' => 'The appointment location may not be greater than 255 characters.',
        ];
    }
}
