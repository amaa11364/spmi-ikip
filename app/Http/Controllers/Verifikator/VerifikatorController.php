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
        
        // CEK UNIT KERJA
        if (!$user->unit_kerja_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki unit kerja. Hubungi admin.');
        }
        
        $unitKerjaId = $user->unit_kerja_id;
        
        // QUERY DENGAN RELASI YANG BENAR (uploader, BUKAN user)
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['uploader', 'unitKerja']);  // PERBAIKAN: ganti 'user' jadi 'uploader'
        
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        } elseif ($request->has('status') && $request->status != '') {
            if ($request->status == 'need_action') {
                $query->whereIn('status', ['pending', 'revision']);
            } else {
                $query->where('status', $request->status);
            }
        } else {
            // Default: tampilkan yang perlu direview
            $query->whereIn('status', ['pending', 'revision']);
        }
        
        // Filter tahapan
        if ($request->filled('tahapan')) {
            $query->where('tahapan', $request->tahapan);
        }
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dokumen', 'like', '%' . $search . '%')
                  ->orWhere('judul', 'like', '%' . $search . '%')
                  ->orWhere('jenis_dokumen', 'like', '%' . $search . '%')
                  // PERBAIKAN: ganti 'user' jadi 'uploader'
                  ->orWhereHas('uploader', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Sorting
        $query->latest();
        
        $dokumens = $query->paginate(20)->withQueryString();
        
        // Hitung statistik untuk badge
        $counts = [
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)->where('status', 'revision')->count(),
        ];
        
        // Statistik status untuk cards
        $statusStats = [
            'pending' => $counts['pending'],
            'approved' => $counts['approved'],
            'rejected' => $counts['rejected'],
            'revision' => $counts['revision'],
        ];
        
        return view('verifikator.dokumen.index', compact('dokumens', 'counts', 'statusStats'));
    }

    /**
     * Menampilkan detail dokumen
     */
    public function reviewDetail($id)
    {
        $user = Auth::user();
        
        // PERBAIKAN: ganti 'user' jadi 'uploader'
        $dokumen = Dokumen::with(['uploader', 'unitKerja', 'comments' => function($query) {
            $query->with('user')->latest();
        }])->findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
            abort(403, 'Anda tidak berhak mengakses dokumen ini.');
        }
        
        return view('verifikator.dokumen.show', compact('dokumen'));
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
        
        if ($dokumen->jenis_upload === 'link') {
            return redirect()->away($dokumen->file_path);
        }
        
        $filePath = storage_path('app/public/' . $dokumen->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return response()->download($filePath, $dokumen->file_name ?? 'dokumen.pdf');
    }

    /**
     * Verifikasi dokumen
     */
    public function verifyDokumen(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,revision',
            'reason' => 'required_if:action,reject,revision|string|nullable',
            'deadline' => 'required_if:action,revision|date|nullable',
            'comment' => 'nullable|string'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        $user = Auth::user();
        
        // Cek akses
        if ($dokumen->unit_kerja_id != $user->unit_kerja_id) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
            return redirect()->back()->with('error', 'Akses ditolak');
        }
        
        $updateData = [
            'verified_by' => $user->id,
            'verified_at' => now(),
        ];
        
        switch ($request->action) {
            case 'approve':
                $updateData['status'] = 'approved';
                $updateData['rejection_reason'] = null;
                $updateData['revision_instructions'] = null;
                $updateData['revision_deadline'] = null;
                break;
                
            case 'reject':
                $updateData['status'] = 'rejected';
                $updateData['rejection_reason'] = $request->reason;
                $updateData['revision_instructions'] = null;
                $updateData['revision_deadline'] = null;
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
                'user_id' => $user->id,
                'content' => $request->comment,
                'type' => $request->action
            ]);
        } elseif ($request->filled('reason')) {
            $dokumen->comments()->create([
                'user_id' => $user->id,
                'content' => $request->reason,
                'type' => $request->action
            ]);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'status' => $request->action]);
        }
        
        $messages = [
            'approve' => 'Dokumen berhasil disetujui.',
            'reject' => 'Dokumen telah ditolak.',
            'revision' => 'Permintaan revisi telah dikirim.'
        ];
        
        return redirect()->route('verifikator.dokumen.index')
            ->with('success', $messages[$request->action]);
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
        
        if (!$user->unit_kerja_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki unit kerja.');
        }
        
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
        
        if (!$user->unit_kerja_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki unit kerja.');
        }
        
        $unitKerjaId = $user->unit_kerja_id;
        
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['uploader', 'unitKerja']);  // PERBAIKAN: ganti 'user' jadi 'uploader'
        
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