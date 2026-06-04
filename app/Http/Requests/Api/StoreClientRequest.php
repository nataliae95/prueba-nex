<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreClientRequest extends FormRequest
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
            'name'      => 'required|string|max:150',
            'taxId' => ['required', 'string', 'max:50', Rule::unique('clients', 'taxId')->ignore($this->client)],
            'status'    => ['required', Rule::in(['activo', 'inactivo', 'prospecto'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'taxId' => 'nit',
            'status' => 'estado'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => $this->name ? ucwords(mb_strtolower(trim($this->name), 'UTF-8')) : null,
            'taxId' => $this->taxId ? strtolower(trim(str_replace(' ', '', $this->taxId))) : null,
            'status' => $this->status ? trim($this->status) : null,
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
