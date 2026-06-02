<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreContactRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => ['nullable', 'email', 'max:150', Rule::unique('contacts', 'email')->ignore($this->contact)],
            'phone'      => 'nullable|string|max:20',
            'position'   => 'nullable|string|max:100',
            'is_primary' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => $this->name ? ucwords(mb_strtolower(trim($this->name), 'UTF-8')) : null,
            'email' => $this->email ? strtolower(trim(str_replace(' ', '', $this->email))) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'position' => $this->position ? trim($this->position) : null,
        ]);
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => 'error',
                'message' => 'Errores de validación en la petición.',
                'errors'  => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY) // 422
        );
    }
}
