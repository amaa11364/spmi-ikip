<?php
// app/Http/Controllers/UPTController.php

namespace App\Http\Controllers;

use App\Models\UPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UPTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $uptList = UPT::orderBy('urutan')->get();
        return view('upt.index', compact('uptList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('upt.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'deskripsi' => 'required|string',
            'ikon' => 'required|string',
            'warna' => 'required|string',
            'kepala_upt' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'jumlah_staff' => 'nullable|integer',
            'jumlah_program' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
            'urutan' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        UPT::create($request->all());

        return redirect()->route('upt.index')
            ->with('success', 'UPT berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $upt = UPT::findOrFail($id);
        return view('upt.show', compact('upt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $upt = UPT::findOrFail($id);
        return view('upt.edit', compact('upt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $upt = UPT::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'deskripsi' => 'required|string',
            'ikon' => 'required|string',
            'warna' => 'required|string',
            'kepala_upt' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'jumlah_staff' => 'nullable|integer',
            'jumlah_program' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
            'urutan' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $upt->update($request->all());

        return redirect()->route('upt.index')
            ->with('success', 'UPT berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $upt = UPT::findOrFail($id);
        $upt->delete();

        return redirect()->route('upt.index')
            ->with('success', 'UPT berhasil dihapus');
    }

    /**
     * Update urutan UPT (for drag and drop)
     */
    public function updateUrutan(Request $request)
    {
        $ids = $request->input('ids');
        
        foreach ($ids as $index => $id) {
            UPT::where('id', $id)->update(['urutan' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}