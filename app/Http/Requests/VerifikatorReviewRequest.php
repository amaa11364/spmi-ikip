<?php
// app/Http/Requests/VerifikatorReviewRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifikatorReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isVerifikator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'nullable|string|max:500',
            'status' => 'sometimes|required|in:approved,rejected,revision',
            'alasan_penolakan' => 'required_if:status,rejected|string|min:10|max:500',
            'instruksi_revisi' => 'required_if:status,revision|string|min:10|max:500',
            'deadline' => 'required_if:status,revision|date|after:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'comment.max' => 'Komentar maksimal 500 karakter.',
            'alasan_penolakan.required_if' => 'Alasan penolakan harus diisi ketika dokumen ditolak.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter.',
            'instruksi_revisi.required_if' => 'Instruksi revisi harus diisi ketika dokumen perlu direvisi.',
            'instruksi_revisi.min' => 'Instruksi revisi minimal 10 karakter.',
            'deadline.required_if' => 'Deadline revisi harus diisi.',
            'deadline.after' => 'Deadline harus setelah hari ini.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('comment') && !$this->has('alasan_penolakan') && $this->status === 'rejected') {
            $this->merge([
                'alasan_penolakan' => $this->comment
            ]);
        }
    }
}