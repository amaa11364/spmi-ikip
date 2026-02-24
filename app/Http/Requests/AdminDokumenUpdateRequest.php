<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminDokumenUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_dokumen' => 'sometimes|required|string|max:255',
            'jenis_dokumen' => 'sometimes|required|string|max:100',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'prodi_id' => 'nullable|exists:prodis,id',
            'iku_id' => 'nullable|exists:ikus,id',
            'tahapan' => [
                'nullable',
                'string',
                Rule::in(['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'])
            ],
            'status' => [
                'sometimes',
                'string',
                Rule::in(['pending', 'approved', 'rejected', 'revision'])
            ],
            'is_public' => 'boolean',
            'metadata' => 'nullable|array',
            'alasan_perubahan' => 'nullable|string|max:500',
            'catatan_admin' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_dokumen.required' => 'Nama dokumen harus diisi.',
            'nama_dokumen.max' => 'Nama dokumen maksimal 255 karakter.',
            'jenis_dokumen.required' => 'Jenis dokumen harus diisi.',
            'tahapan.in' => 'Tahapan tidak valid. Pilih: penetapan, pelaksanaan, evaluasi, pengendalian, peningkatan.',
            'status.in' => 'Status tidak valid. Pilih: pending, approved, rejected, revision.',
            'unit_kerja_id.exists' => 'Unit kerja tidak ditemukan.',
            'iku_id.exists' => 'IKU tidak ditemukan.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set is_public default false jika tidak ada
        if (!$this->has('is_public')) {
            $this->merge([
                'is_public' => false
            ]);
        }
    }
}