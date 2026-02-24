<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTahapanNotModified
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
        
        // Verifikator tidak boleh mengubah tahapan
        if ($user && $user->isVerifikator()) {
            // Cek jika request memiliki field tahapan
            if ($request->has('tahapan')) {
                // Jika ada ID di route, cek apakah tahapan berubah
                $dokumenId = $this->getDokumenIdFromRoute($request);
                
                if ($dokumenId) {
                    $dokumen = \App\Models\Dokumen::find($dokumenId);
                    
                    if ($dokumen && $dokumen->tahapan != $request->tahapan) {
                        // Verifikator mencoba mengubah tahapan - tolak
                        if ($request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Verifikator tidak diizinkan mengubah tahapan dokumen.'
                            ], 403);
                        }
                        
                        return back()->with('error', 'Verifikator tidak diizinkan mengubah tahapan dokumen.');
                    }
                }
            }
            
            // Cek juga di input tersembunyi (method spoofing)
            if ($request->isMethod('put') || $request->isMethod('patch')) {
                $originalTahapan = $this->getOriginalTahapan($request);
                $newTahapan = $request->input('tahapan');
                
                if ($originalTahapan && $newTahapan && $originalTahapan != $newTahapan) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Verifikator tidak diizinkan mengubah tahapan dokumen.'
                        ], 403);
                    }
                    
                    return back()->with('error', 'Verifikator tidak diizinkan mengubah tahapan dokumen.');
                }
            }
        }
        
        // Admin boleh mengubah tahapan (untuk keperluan manajemen)
        
        return $next($request);
    }
    
    /**
     * Get dokumen ID from route
     */
    private function getDokumenIdFromRoute(Request $request): ?int
    {
        $routeParams = $request->route()->parameters();
        
        foreach ($routeParams as $key => $value) {
            if (is_numeric($value) && $this->isDokumenParameter($key)) {
                return (int) $value;
            }
            
            if (is_object($value) && get_class($value) === 'App\Models\Dokumen') {
                return $value->id;
            }
        }
        
        return null;
    }
    
    /**
     * Get original tahapan value from database
     */
    private function getOriginalTahapan(Request $request): ?string
    {
        $dokumenId = $this->getDokumenIdFromRoute($request);
        
        if ($dokumenId) {
            $dokumen = \App\Models\Dokumen::find($dokumenId);
            return $dokumen ? $dokumen->tahapan : null;
        }
        
        return null;
    }
    
    /**
     * Check if parameter is dokumen parameter
     */
    private function isDokumenParameter($key): bool
    {
        $dokumenKeys = ['id', 'dokumen', 'dokumen_id', 'document', 'document_id'];
        return in_array(strtolower($key), $dokumenKeys);
    }
}