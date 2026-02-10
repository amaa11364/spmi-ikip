<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verifikator']);
    }

    // Halaman utama dokumen yang perlu diverifikasi
    public function index(Request $request)
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $query = Dokumen::where('unit_kerja_id', $unitKerjaId)
            ->with(['user', 'unitKerja'])
            ->latest();
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan jenis dokumen
        if ($request->filled('jenis')) {
            $query->where('jenis_dokumen', $request->jenis);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $dokumens = $query->paginate(20);
        $statuses = ['pending', 'approved', 'rejected', 'revision'];
        
        return view('verifikator.dokumen.index', compact('dokumens', 'statuses'));
    }

    // Detail dokumen untuk review
    public function show($id)
    {
        $dokumen = Dokumen::with(['user', 'unitKerja', 'comments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        // Cek apakah dokumen dari unit kerja verifikator
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            abort(403, 'Anda tidak berhak mengakses dokumen ini.');
        }
        
        return view('verifikator.dokumen.show', compact('dokumen'));
    }

    // Approve dokumen
    public function approve(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'nullable|string|max:500'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            return back()->with('error', 'Akses ditolak.');
        }
        
        $dokumen->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
        
        // Tambahkan komentar jika ada
        if ($request->komentar) {
            $dokumen->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->komentar,
                'type' => 'verification'
            ]);
        }
        
        return redirect()->route('verifikator.dokumen.index')
            ->with('success', 'Dokumen berhasil disetujui.');
    }

    // Reject dokumen
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:500',
            'komentar' => 'nullable|string|max:500'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            return back()->with('error', 'Akses ditolak.');
        }
        
        $dokumen->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->alasan_penolakan,
        ]);
        
        // Tambahkan komentar jika ada
        if ($request->komentar) {
            $dokumen->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->komentar,
                'type' => 'rejection'
            ]);
        }
        
        return redirect()->route('verifikator.dokumen.index')
            ->with('success', 'Dokumen telah ditolak.');
    }

    // Minta revisi dokumen
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'instruksi_revisi' => 'required|string|min:10|max:500',
            'deadline' => 'required|date|after:today',
            'komentar' => 'nullable|string|max:500'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        // Cek akses
        if ($dokumen->unit_kerja_id != Auth::user()->unit_kerja_id) {
            return back()->with('error', 'Akses ditolak.');
        }
        
        $dokumen->update([
            'status' => 'revision',
            'revision_instructions' => $request->instruksi_revisi,
            'revision_deadline' => $request->deadline,
            'verified_by' => Auth::id(),
        ]);
        
        // Tambahkan komentar jika ada
        if ($request->komentar) {
            $dokumen->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->komentar,
                'type' => 'revision'
            ]);
        }
        
        return redirect()->route('verifikator.dokumen.index')
            ->with('success', 'Permintaan revisi telah dikirim.');
    }

    // Tambah komentar
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:5|max:500'
        ]);
        
        $dokumen = Dokumen::findOrFail($id);
        
        $dokumen->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'type' => 'comment'
        ]);
        
        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    // Statistik dokumen
    public function statistics()
    {
        $user = Auth::user();
        $unitKerjaId = $user->unit_kerja_id;
        
        $statistics = [
            'total' => Dokumen::where('unit_kerja_id', $unitKerjaId)->count(),
            'pending' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'pending')->count(),
            'approved' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'approved')->count(),
            'rejected' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'rejected')->count(),
            'revision' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->where('status', 'revision')->count(),
            'by_type' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->selectRaw('jenis_dokumen, count(*) as count')
                ->groupBy('jenis_dokumen')
                ->get(),
            'by_month' => Dokumen::where('unit_kerja_id', $unitKerjaId)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
        
        return view('verifikator.dokumen.statistics', compact('statistics'));
    }
}