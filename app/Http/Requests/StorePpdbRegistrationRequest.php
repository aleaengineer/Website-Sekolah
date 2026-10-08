<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePpdbRegistrationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:100'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:L,P'],
            'previous_school' => ['required', 'string', 'max:150'],
            'parent_name' => ['required', 'string', 'max:100'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'jalur' => ['required', 'in:zonasi,afirmasi,prestasi,mutasi'],
        ];
    }
}
