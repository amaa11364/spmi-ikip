<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = Jadwal::with('user')
            ->latest()
            ->paginate(10);
            
        return view('admin.jadwal.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jadwal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        // Set default values
        $validated['user_id'] = auth()->id();
        $validated['status'] = $this->determineStatus($validated['tanggal']);
        $validated['is_active'] = $request->has('is_active');
        
        // Set default warna jika tidak ada
        if (empty($validated['warna'])) {
            $validated['warna'] = '#0d6efd'; // Bootstrap primary blue
        }

        Jadwal::create($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        return view('admin.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'nullable|in:akan_datang,sedang_berlangsung,selesai,dibatalkan',
            'is_active' => 'nullable|boolean'
        ]);

        // Auto-update status based on tanggal if not manually set
        if (!isset($validated['status'])) {
            $validated['status'] = $this->determineStatus($validated['tanggal']);
        }

        $validated['is_active'] = $request->has('is_active');

        $jadwal->update($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Determine status based on date
     */
    private function determineStatus($tanggal)
    {
        $tanggal = \Carbon\Carbon::parse($tanggal);
        
        if ($tanggal->isToday()) {
            return 'sedang_berlangsung';
        } elseif ($tanggal->isPast()) {
            return 'selesai';
        } else {
            return 'akan_datang';
        }
    }

    /**
     * Update status for all jadwal (untuk dijalankan via scheduler)
     */
    public function updateAllStatuses()
    {
        $jadwals = Jadwal::where('status', '!=', 'dibatalkan')->get();
        foreach ($jadwals as $jadwal) {
            $jadwal->updateStatus();
        }
        
        return response()->json(['message' => 'Status updated successfully']);
    }
}