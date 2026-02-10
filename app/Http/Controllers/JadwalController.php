<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::latest()->paginate(10);
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('admin.jadwal.create');
    }

public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'nama_kegiatan' => 'required|max:255', // Ganti 'kegiatan' dengan 'nama_kegiatan'
        'status' => 'nullable|in:aktif,selesai,dibatalkan'
    ]);

    $data = $request->only(['tanggal', 'nama_kegiatan', 'deskripsi', 'waktu', 'lokasi', 'penanggung_jawab', 'status', 'kategori']);
    $data['user_id'] = auth()->id();
    $data['status'] = 'aktif'; // Default aktif

    Jadwal::create($data);

    return redirect()->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil ditambahkan');
}

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|max:255',
            'deskripsi' => 'nullable',
            'tempat' => 'nullable|max:100',
            'waktu' => 'required|date_format:H:i',
            'warna' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}