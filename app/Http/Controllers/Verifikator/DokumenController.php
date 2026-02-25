<?php
// app/Http/Controllers/Admin/DokumenController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    /**
     * Display a listing of documents for admin
     */
    public function index(Request $request)
    {
        $query = Dokumen::with(['unitKerja', 'uploader', 'iku'])
                       ->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan tahapan
        if ($request->filled('tahapan')) {
            $query->where('tahapan', $request->tahapan);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $dokumens = $query->paginate(20)->withQueryString();
        
        // Statistics
        $statistics = [
            'total' => Dokumen::count(),
            'pending' => Dokumen::where('status', 'pending')->count(),
            'approved' => Dokumen::where('status', 'approved')->count(),
            'rejected' => Dokumen::where('status', 'rejected')->count(),
        ];
        
        return view('admin.dokumen.index', compact('dokumens', 'statistics'));
    }

    /**
     * Verify document (approve/reject)
     */
    public function verify(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'status' => 'required|in:approved,rejected',
                'catatan' => 'nullable|string|max:500',
            ]);

            $dokumen = Dokumen::findOrFail($id);
            
            $dokumen->update([
                'status' => $request->status,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_reason' => $request->status === 'rejected' ? $request->catatan : null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diverifikasi.',
                'status' => $request->status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle public status
     */
    public function togglePublic($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);
            
            $dokumen->update([
                'is_public' => !$dokumen->is_public
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status publik berhasil diubah.',
                'is_public' => $dokumen->is_public
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status publik: ' . $e->getMessage()
            ], 500);
        }
    }
}