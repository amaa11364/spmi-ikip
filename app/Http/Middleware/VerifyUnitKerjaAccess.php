<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyUnitKerjaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Jika bukan verifikator, lewati (biarkan middleware lain yang handle)
        if (!$user || !$user->isVerifikator()) {
            return $next($request);
        }
        
        // Verifikator harus memiliki unit_kerja_id
        if (!$user->unit_kerja_id) {
            abort(403, 'Akun verifikator Anda belum memiliki unit kerja. Hubungi admin.');
        }
        
        // Cek akses ke parameter route yang berisi ID dokumen
        $routeParams = $request->route()->parameters();
        
        foreach ($routeParams as $key => $value) {
            // Jika parameter adalah ID dokumen (integer)
            if (is_numeric($value) && $this->isDokumenParameter($key)) {
                if (!$this->canAccessDokumen($value, $user->unit_kerja_id)) {
                    abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
                }
            }
            
            // Jika parameter adalah model Dokumen (route model binding)
            if (is_object($value) && get_class($value) === 'App\Models\Dokumen') {
                if ($value->unit_kerja_id != $user->unit_kerja_id) {
                    abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
                }
            }
        }
        
        // Cek akses ke query parameters untuk filtering
        if ($request->has('unit_kerja_id') && $request->unit_kerja_id != $user->unit_kerja_id) {
            // Verifikator hanya boleh melihat unit kerjanya sendiri
            if ($request->unit_kerja_id != 'all') {
                return redirect()->to($request->url() . '?unit_kerja_id=' . $user->unit_kerja_id);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Cek apakah parameter adalah ID dokumen
     */
    private function isDokumenParameter($key): bool
    {
        $dokumenKeys = ['id', 'dokumen', 'dokumen_id', 'document', 'document_id'];
        return in_array(strtolower($key), $dokumenKeys);
    }
    
    /**
     * Cek akses ke dokumen berdasarkan unit kerja
     */
    private function canAccessDokumen($dokumenId, $unitKerjaId): bool
    {
        $dokumen = \App\Models\Dokumen::find($dokumenId);
        
        if (!$dokumen) {
            return false;
        }
        
        return $dokumen->unit_kerja_id == $unitKerjaId;
    }
}