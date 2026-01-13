<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\GenderEnum;
use Illuminate\Validation\Rule;
use App\Enums\BloodTypeEnum;

class RegisterUserRequest extends FormRequest
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
            'full_name' => ['required','string','min:10','max:255'],
            'gender' =>['required',Rule::enum(GenderEnum::class)],
            'birth_date' => ['required', Rule::date()->format('Y-m-d')],
            'blood_type'  => ['required',Rule::enum(BloodTypeEnum::class)], 
            'height_cm'   => ['required','integer','min:30','max:250'],    
            'weight_kg'   => ['required','numeric','min:1','max:200'], 
            'chronic_diseases'  => ['required','string','max:255'],
            'allergies'   => ['required','string','max:255'],
            'current_medication' => ['required','string','max:255'],
            'email' => ['required','string','email','unique:users'],
            'password' => ['required','string','min:8','max:16','confirmed'],
            'address' => ['required','string','max:255'],
            'phone_number' => ['required','string','min:8','max:10','unique:users'],   
            'extra_info'  => ['nullable','string','max:255'],           
        ];
    }
    public function messages(): array
    {
        return [
            'full_name.required' => 'full name field is required',
            'full_name.min' => 'full name field must be at least :min characters',
            'gender.required' => 'gender field is required',
            'birth_date.required' => 'birth date is required',
            'email.required' =>  'email field is required',
            'password.required' => 'password field is required',
            'password.min' => 'password must be at least :min characters',
            'password.max' => 'password must be at most :max characters',
            'password.confirmation' => 'verify password field does not match the password above',
            'phone_number.required' =>'phone number field is required',
            'phone_number.min' => 'the number of digits in phone number field must be least 8 digits',
            'phone_number.max' => 'the number of digits in phone number field is 10 digits maximum',

        ];
    }
}
