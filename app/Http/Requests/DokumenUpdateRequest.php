<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DokumenUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization sudah di middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $dokumen = $this->route('dokumen');
        
        $rules = [
            'nama_dokumen' => 'sometimes|required|string|max:255',
            'jenis_dokumen' => 'sometimes|required|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'iku_id' => 'nullable|exists:ikus,id',
        ];
        
        // Admin bisa update tahapan
        if ($user->isAdmin()) {
            $rules['tahapan'] = [
                'sometimes',
                'required',
                Rule::in(['penetapan', 'pelaksanaan', 'evaluasi', 'pengendalian', 'peningkatan'])
            ];
        }
        
        // Verifikator hanya bisa update status
        if ($user->isVerifikator()) {
            $rules['status'] = [
                'required',
                Rule::in(['approved', 'rejected', 'revision'])
            ];
            
            $rules['komentar'] = 'nullable|string|max:500';
            
            // Jika reject, wajib ada alasan
            if ($this->status === 'rejected') {
                $rules['alasan_penolakan'] = 'required|string|min:10|max:500';
            }
            
            // Jika revision, wajib ada instruksi
            if ($this->status === 'revision') {
                $rules['instruksi_revisi'] = 'required|string|min:10|max:500';
                $rules['deadline'] = 'required|date|after:today';
            }
        }
        
        // User hanya bisa update dokumen miliknya yang statusnya revision
        if ($user->isUser() && $dokumen && $dokumen->uploaded_by == $user->id) {
            if ($dokumen->status === 'revision') {
                $rules['file_dokumen'] = 'sometimes|file|max:10240|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png';
                $rules['keterangan_revisi'] = 'nullable|string|max:500';
            }
        }
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_dokumen.required' => 'Nama dokumen harus diisi.',
            'nama_dokumen.max' => 'Nama dokumen maksimal 255 karakter.',
            'tahapan.in' => 'Tahapan tidak valid. Pilih: penetapan, pelaksanaan, evaluasi, pengendalian, peningkatan.',
            'status.in' => 'Status tidak valid.',
            'alasan_penolakan.required' => 'Alasan penolakan harus diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter.',
            'instruksi_revisi.required' => 'Instruksi revisi harus diisi.',
            'deadline.required' => 'Deadline revisi harus diisi.',
            'deadline.after' => 'Deadline harus setelah hari ini.',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB.',
            'file_dokumen.mimes' => 'Format file tidak didukung.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default values atau transform data
        if ($this->has('status') && $this->status === 'rejected' && !$this->has('alasan_penolakan')) {
            $this->merge([
                'alasan_penolakan' => $this->komentar
            ]);
        }
    }
}