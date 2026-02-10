<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Prodi;

class VerifikatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verifikator']);
    }

    // Dashboard Verifikator
    public function dashboard()
    {
        $totalDokumen = Dokumen::count();
        $pendingReview = Dokumen::where('status', 'pending')->count();
        $prodiCount = Prodi::count();
        
        $statistics = [
            'total_dokumen' => $totalDokumen,
            'pending_review' => $pendingReview,
            'total_prodi' => $prodiCount,
            'dokumen_terbaru' => Dokumen::latest()->take(5)->get()
        ];
        
        return view('verifikator.dashboard', compact('statistics'));
    }

    // Review semua dokumen (bisa filter by prodi)
    public function reviewDokumen(Request $request)
    {
        $query = Dokumen::with(['uploader', 'prodi']);
        
        // Filter by prodi jika ada
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }
        
        // Filter by status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $dokumens = $query->latest()->paginate(20);
        $prodis = Prodi::all();
        
        return view('verifikator.review.index', compact('dokumens', 'prodis'));
    }

    // Approve dokumen
    public function approveDokumen($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        $dokumen->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
        
        return back()->with('success', 'Dokumen berhasil diverifikasi.');
    }

    // Reject dokumen dengan alasan
    public function rejectDokumen(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        $dokumen->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        return back()->with('success', 'Dokumen ditolak.');
    }

    // Lihat statistik per prodi
    public function statistikProdi()
    {
        $statistics = Prodi::withCount(['dokumens'])
            ->withCount(['dokumens as dokumen_pending' => function($query) {
                $query->where('status', 'pending');
            }])
            ->withCount(['dokumens as dokumen_approved' => function($query) {
                $query->where('status', 'approved');
            }])
            ->get();
        
        return view('verifikator.statistik', compact('statistics'));
    }
}