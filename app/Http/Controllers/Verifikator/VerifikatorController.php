<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verifikator']);
    }

    /**
     * Menampilkan daftar dokumen untuk review
     */
    public function reviewDokumen(Request $request, $status = null)
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['user', 'unitKerja']);
        
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        } elseif ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_dokumen', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $dokumens = $query->latest()->paginate(20);
        
        // Hitung statistik untuk badge
        $counts = [
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'revision')->count(),
        ];
        
        return view('verifikator.review.index', compact('dokumens', 'counts'));
    }

    /**
     * Menampilkan detail dokumen
     */
    public function reviewDetail($id)
    {
        $dokumen = Dokumen::with(['user', 'unitKerja', 'comments' => function($query) {
            $query->with('user')->latest();
        }])->findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            abort(403, 'Anda tidak berhak mengakses dokumen ini.');
        }
        
        return view('verifikator.review.detail', compact('dokumen'));
    }

    /**
     * Alias untuk reviewDokumen (dokumen list)
     */
    public function dokumenList(Request $request, $status = null)
    {
        return $this->reviewDokumen($request, $status);
    }

    /**
     * Alias untuk reviewDetail
     */
    public function viewDokumen($id)
    {
        return $this->reviewDetail($id);
    }

    /**
     * Download dokumen
     */
    public function downloadDokumen($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            abort(403);
        }
        
        $filePath = storage_path('app/public/' . $dokumen->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return response()->download($filePath, $dokumen->nama_file);
    }

    /**
     * Verifikasi dokumen
     */
    public function verifyDokumen(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,revision',
            'reason' => 'required_if:action,reject,revision|string|nullable',
            'deadline' => 'required_if:action,revision|date|nullable'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }
        
        $updateData = [
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ];
        
        switch ($request->action) {
            case 'approve':
                $updateData['status'] = 'approved';
                $updateData['rejection_reason'] = null;
                $updateData['revision_instructions'] = null;
                break;
                
            case 'reject':
                $updateData['status'] = 'rejected';
                $updateData['rejection_reason'] = $request->reason;
                $updateData['revision_instructions'] = null;
                break;
                
            case 'revision':
                $updateData['status'] = 'revision';
                $updateData['revision_instructions'] = $request->reason;
                $updateData['revision_deadline'] = $request->deadline;
                $updateData['rejection_reason'] = null;
                break;
        }
        
        $dokumen->update($updateData);
        
        // Tambah komentar
        if ($request->filled('comment')) {
            $dokumen->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->comment,
                'type' => $request->action
            ]);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('verifikator.dokumen.index')
            ->with('success', 'Dokumen berhasil diverifikasi.');
    }

    /**
     * Hitung pending count
     */
    public function getPendingCount()
    {
        $count = Dokumen::where('unit_kerja_id', Auth::user()->unit_kerja_id)
            ->where('status', 'pending')
            ->count();
            
        return response()->json(['count' => $count]);
    }

    /**
     * Statistik
     */
    public function statistik()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $statistics = [
            'total' => Dokumen::where('unit_kerja_id', $unitKerjaId)->count(),
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'revision')->count(),
        ];
        
        return view('verifikator.statistik.index', compact('statistics'));
    }

    /**
     * Laporan
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['user', 'unitKerja']);
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $dokumens = $query->latest()->get();
        
        return view('verifikator.laporan.index', compact('dokumens'));
    }

    public function exportPdf(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export PDF dalam pengembangan');
    }

    public function exportExcel(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export Excel dalam pengembangan');
    }
}