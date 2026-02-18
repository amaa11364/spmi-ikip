<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user || $user->is_admin || $user->is_verifikator) {
            abort(403, 'Unauthorized access');
        }
        
        $statistics = [
            'my_documents' => $user->dokumens()->count(),
            'storage_used' => $this->getFormattedStorageUsed($user->id),
            'recent_uploads' => $user->dokumens()->latest()->take(5)->get(),
            'pending_approvals' => $user->dokumens()->where('status', 'pending')->count(),
            'approved_documents' => $user->dokumens()->where('status', 'approved')->count(),
            'rejected_documents' => $user->dokumens()->where('status', 'rejected')->count(),
            'revision_documents' => $user->dokumens()->where('status', 'revision')->count(),
        ];
        
        // Statistik per bulan
        $monthlyStats = $user->dokumens()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');
        
        return view('user.dashboard', compact('statistics', 'monthlyStats'));
    }
    
    private function getFormattedStorageUsed($userId)
    {
        $totalSize = Dokumen::where('uploaded_by', $userId)->sum('file_size');
        
        if ($totalSize < 1024) {
            return $totalSize . ' B';
        } elseif ($totalSize < 1048576) {
            return round($totalSize / 1024, 2) . ' KB';
        } elseif ($totalSize < 1073741824) {
            return round($totalSize / 1048576, 2) . ' MB';
        } else {
            return round($totalSize / 1073741824, 2) . ' GB';
        }
    }
}