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

   // app/Http\Controllers/Admin/JadwalController.php - method store()
public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'kegiatan' => 'required|max:255',
        'is_active' => 'boolean'
    ]);

    $data = $request->only(['tanggal', 'kegiatan']);
    $data['user_id'] = auth()->id();
    $data['is_active'] = true; // Auto aktif
    $data['waktu'] = '08:00'; // Default waktu
    $data['urutan'] = 1; // Default urutan

    Jadwal::create($data);

    return redirect()->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil ditambahkan');
}
    public function edit(Jadwal $jadwal)
    {
        return view('admin.jadwal.edit', compact('jadwal'));
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