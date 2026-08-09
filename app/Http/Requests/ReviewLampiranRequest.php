<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewLampiranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->jabatan_aktif === 'PP';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_validasi' => ['required', 'string', 'in:pending,disetujui,revisi'],
            'catatan' => ['required_if:status_validasi,revisi', 'nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'catatan.required_if' => 'Catatan/Komentar wajib diisi jika status dokumen ditandai sebagai Revisi.',
        ];
    }
}
