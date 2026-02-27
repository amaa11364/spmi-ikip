<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     * Hanya untuk admin (dengan middleware auth)
     */
    public function index()
    {
        $beritas = Berita::latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new resource.
     * Hanya untuk admin
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     * Hanya untuk admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'is_published' => 'boolean'
        ]);

        $data = [
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'isi' => $request->judul,
            'ringkasan' => $request->judul,
            'user_id' => auth()->id(),
            'views' => 0,
            'is_published' => $request->has('is_published') ? true : false,
        ];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/berita', $filename);
            $data['gambar'] = 'berita/' . $filename;
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     * Hanya untuk admin
     */
    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     * Hanya untuk admin
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'nullable',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean'
        ]);

        $berita = Berita::findOrFail($id);
        
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
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     * Hanya untuk admin
     */
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

    /**
     * Publish berita (admin)
     */
    public function publish($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->publish();
        
        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dipublikasikan');
    }

    /**
     * Unpublish berita (admin)
     */
    public function unpublish($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->unpublish();
        
        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditarik dari publikasi');
    }

    // ==================== METHOD UNTUK PUBLIK (TANPA AUTH) ====================

    /**
     * Display a listing of berita for public (without auth).
     * Menampilkan berita dalam bentuk card layout, hanya yang dipublikasikan.
     */
    public function publikIndex()
    {
        // Query Eloquent: hanya tampilkan berita dengan is_published = true, urutkan terbaru
        $beritas = Berita::where('is_published', true)
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);
        
        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Display the specified berita for public.
     */
    public function publikShow($id)
    {
        // Query Eloquent: cari berita berdasarkan ID dan pastikan sudah dipublikasikan
        $berita = Berita::where('id', $id)
                        ->where('is_published', true)
                        ->firstOrFail();
        
        // Increment views
        $berita->incrementViews();
        
        // Ambil berita terkait (3 berita terbaru lainnya)
        $beritaLainnya = Berita::where('is_published', true)
                                ->where('id', '!=', $berita->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
        
        return view('admin.berita.show', compact('berita', 'beritaLainnya'));
    }
}