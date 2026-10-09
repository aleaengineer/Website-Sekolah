<?php

namespace App\Http\Requests;

use App\Support\MathCaptcha;
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
            'parent_email' => ['nullable', 'email', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'jalur' => ['required', 'string', 'exists:ppdb_jalurs,slug'],
            'ppdb_wave_id' => ['required', 'integer', 'exists:ppdb_waves,id'],
            'kk_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'akta_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'rapor_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'captcha' => ['required', 'integer', function ($attribute, $value, $fail): void {
                if (! MathCaptcha::check('ppdb', $value)) {
                    $fail('Jawaban captcha salah. Silakan coba lagi.');
                }
            }],
        ];
    }
}
