<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link' => 'required|url',
        'is_published' => 'boolean'
    ]);

    $data = [
        'judul' => $request->judul,
        'slug' => Str::slug($request->judul),
        'isi' => $request->judul, // ✅ GANTI 'konten' menjadi 'isi'
        'ringkasan' => $request->judul,
        'user_id' => auth()->id(),
        'views' => 0,
        'is_published' => true,
    ];

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/berita', $filename);
        $data['gambar'] = 'berita/' . $filename;
        $data['gambar_url'] = asset('storage/berita/' . $filename);
    } else {
        $data['gambar_url'] = asset('images/default-news.jpg');
    }

    Berita::create($data);

    return redirect()->route('admin.berita.index')
        ->with('success', 'Berita berhasil ditambahkan');
}

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id) // ← Gunakan $id, bukan Berita $berita
{
    $request->validate([
        'judul' => 'required|max:255',
        'ringkasan' => 'nullable',
        'konten' => 'required',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_published' => 'boolean'
    ]);

    $berita = Berita::findOrFail($id); // ← Cari berdasarkan ID
    
    $data = [
        'judul' => $request->judul,
        'ringkasan' => $request->ringkasan,
        'isi' => $request->konten,
        'slug' => Str::slug($request->judul),
        'is_published' => $request->has('is_published') ? true : false,
    ];

    if ($request->hasFile('gambar')) {
        // Hapus gambar lama
        if ($berita->gambar && Storage::exists('public/' . $berita->gambar)) {
            Storage::delete('public/' . $berita->gambar);
        }

        $file = $request->file('gambar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/berita', $filename);
        $data['gambar'] = 'berita/' . $filename;
        $data['gambar_url'] = asset('storage/berita/' . $filename);
    }

    $berita->update($data);

    return redirect()->route('admin.berita.index')
        ->with('success', 'Berita berhasil diperbarui');
}

    public function destroy(Berita $berita)
    {
        // Hapus gambar
        if ($berita->gambar && Storage::exists('public/' . $berita->gambar)) {
            Storage::delete('public/' . $berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus');
    }
}