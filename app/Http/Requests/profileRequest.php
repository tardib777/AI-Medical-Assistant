<?php

namespace App\Http\Requests;

use App\Enums\BloodTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class profileRequest extends FormRequest
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
            'blood_type'  => ['required',Rule::enum(BloodTypeEnum::class)], 
            'height_cm'   => ['required','integer','min:30','max:250'],    
            'weight_kg'   => ['required','numeric','min:1','max:200'],    
            'chronic_diseases'  => ['required','string','max:255'],
            'allergies'   => ['required','string','max:255'],
            'current_medication' => ['required','string','max:255'],
            'extra_info'  => ['nullable','string','max:255'],           
        ];
    }
   

}
