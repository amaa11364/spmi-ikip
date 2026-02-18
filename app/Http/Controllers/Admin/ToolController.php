<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function backup()
    {
        return view('admin.tools.backup');
    }

    public function createBackup(Request $request)
    {
        // Buat backup
    }

    public function logs()
    {
        return view('admin.tools.logs');
    }

    public function cacheClear()
    {
        // Clear cache
    }

    public function storageLink()
    {
        // Buat storage link
    }
}