<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function userActivity()
    {
        return view('admin.reports.user-activity');
    }

    public function dokumenActivity()
    {
        return view('admin.reports.dokumen-activity');
    }

    public function verificationStats()
    {
        return view('admin.reports.verification-stats');
    }

    public function exportUsers()
    {
        // Export users
    }

    public function exportDokumen()
    {
        // Export dokumen
    }

    public function exportVerification()
    {
        // Export verification
    }
}