<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        return view('user.dokumen.index');
    }

    public function show($id)
    {
        return view('user.dokumen.show');
    }

    public function destroy($id)
    {
        // Hapus dokumen
    }

    public function edit($id)
    {
        return view('user.dokumen.edit');
    }

    public function update(Request $request, $id)
    {
        // Update dokumen
    }

    public function togglePublic($id)
    {
        // Toggle public status
    }

    public function export()
    {
        // Export dokumen
    }
}