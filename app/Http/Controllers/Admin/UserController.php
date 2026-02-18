<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Simpan user
    }

    public function show($id)
    {
        return view('admin.users.show');
    }

    public function edit($id)
    {
        return view('admin.users.edit');
    }

    public function update(Request $request, $id)
    {
        // Update user
    }

    public function destroy($id)
    {
        // Hapus user
    }

    public function activate($id)
    {
        // Aktifkan user
    }

    public function deactivate($id)
    {
        // Nonaktifkan user
    }

    public function changeRole($id)
    {
        // Ubah role
    }
}