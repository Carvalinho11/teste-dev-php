<?php

namespace App\Http\Requests\Api\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SupplierStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->filled('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D/', '', $this->input('phone')),
            ]);
        }
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
            'document' => [
                'required',
                'string',
                'regex:/^(\d{11}|\d{14})$/',
                'unique:suppliers,document',
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => [
                'required',
                'string',
                'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/',
                'max:20'
            ],
            'address' => ['required', 'array'],
            'address.zipcode' => [
                'required',
                'string',
                'regex:/^\d{5}-?\d{3}$/',
            ],
            'address.street' => ['required', 'string', 'max:255'],
            'address.number' => ['required', 'string', 'max:50'],
            'address.complement' => ['nullable', 'string', 'max:255'],
            'address.neighborhood' => ['required', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:255'],
            'address.state' => ['required', 'string', 'size:2'],
        ];
    }
}
