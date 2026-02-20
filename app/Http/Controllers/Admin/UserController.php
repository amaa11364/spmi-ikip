<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with(['unitKerja', 'programStudi'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $unitKerja = UnitKerja::all();
        $programStudi = ProgramStudi::all();
        return view('admin.users.create', compact('unitKerja', 'programStudi'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,verifikator,user',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
            'permissions' => 'nullable|array'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = basename($avatarPath);
        }

        // Set default values
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        
        // Handle permissions as JSON
        if ($request->has('permissions')) {
            $validated['permissions'] = $request->permissions;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with(['unitKerja', 'programStudi', 'dokumens'])
                    ->findOrFail($id);
        
        $recentDocuments = $user->dokumens()
                                ->latest()
                                ->take(5)
                                ->get();
        
        return view('admin.users.show', compact('user', 'recentDocuments'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $unitKerja = UnitKerja::all();
        $programStudi = ProgramStudi::all();
        return view('admin.users.edit', compact('user', 'unitKerja', 'programStudi'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,verifikator,user',
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
            'permissions' => 'nullable|array'
        ]);

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = basename($avatarPath);
        }

        $validated['is_active'] = $request->has('is_active');
        
        // Handle permissions
        $validated['permissions'] = $request->permissions ?? [];

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself or other admins
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Anda tidak dapat menghapus akun Anda sendiri.'
            ], 403);
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'error' => 'Tidak dapat menghapus admin terakhir.'
            ], 403);
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $user->delete();

        return response()->json([
            'success' => 'Pengguna berhasil dihapus.'
        ]);
    }

    /**
     * Activate user account.
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return response()->json([
            'success' => 'Akun pengguna berhasil diaktifkan.'
        ]);
    }

    /**
     * Deactivate user account.
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'
            ], 403);
        }

        $user->update(['is_active' => false]);

        return response()->json([
            'success' => 'Akun pengguna berhasil dinonaktifkan.'
        ]);
    }

    /**
     * Change user role.
     */
    public function changeRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,verifikator,user'
        ]);

        $user = User::findOrFail($id);

        // Prevent changing your own role
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Role pengguna berhasil diubah.');
    }

    /**
     * Bulk action on users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $count = count($request->user_ids);

        switch ($request->action) {
            case 'activate':
                User::whereIn('id', $request->user_ids)
                    ->where('id', '!=', auth()->id()) // Exclude current user
                    ->update(['is_active' => true]);
                $message = "$count pengguna berhasil diaktifkan.";
                break;
                
            case 'deactivate':
                User::whereIn('id', $request->user_ids)
                    ->where('id', '!=', auth()->id())
                    ->update(['is_active' => false]);
                $message = "$count pengguna berhasil dinonaktifkan.";
                break;
                
            case 'delete':
                // Prevent deleting current user and last admin
                $users = User::whereIn('id', $request->user_ids)
                             ->where('id', '!=', auth()->id())
                             ->get();
                
                foreach ($users as $user) {
                    if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
                        continue;
                    }
                    
                    if ($user->avatar) {
                        Storage::disk('public')->delete('avatars/' . $user->avatar);
                    }
                    $user->delete();
                }
                
                $message = "Pengguna yang dipilih berhasil dihapus.";
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}