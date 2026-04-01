<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmProfileRequest extends FormRequest
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
            'business_name'  => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'years_running'  => 'required|string|max:255',
            'employee_count' => 'required|integer|min:0',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'province'       => 'required|string|max:255',
            'omzet'          => 'required|numeric|min:0',
        ];
    }
}
