<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Debug: lihat apa yang dikirim form
        \Log::info('=== PROFILE UPDATE START ===');
        \Log::info('Request Data:', $request->all());
        \Log::info('Has File Avatar:', ['has_file' => $request->hasFile('avatar')]);
        \Log::info('User ID:', ['user_id' => Auth::id()]);

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            \Log::info('Validation Failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        \Log::info('Validation Passed');

        // Update nama user
        $user->name = $request->name;
        \Log::info('Name to update:', ['name' => $request->name]);

        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            
            \Log::info('Avatar File Details:', [
                'original_name' => $avatarFile->getClientOriginalName(),
                'extension' => $avatarFile->getClientOriginalExtension(),
                'size' => $avatarFile->getSize(),
                'mime_type' => $avatarFile->getMimeType(),
            ]);

            // Validasi file
            if ($avatarFile->isValid()) {
                // Hapus avatar lama jika ada
                if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
                    Storage::delete('public/avatars/' . $user->avatar);
                    \Log::info('Old avatar deleted');
                }

                // Generate nama file yang unik
                $avatarName = 'avatar_' . $user->id . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
                
                \Log::info('New avatar name:', ['avatar_name' => $avatarName]);
                
                // Simpan file ke storage
                $avatarPath = $avatarFile->storeAs('public/avatars', $avatarName);
                
                if ($avatarPath) {
                    // Update nama file di database
                    $user->avatar = $avatarName;
                    \Log::info('Avatar saved to storage:', ['path' => $avatarPath]);
                } else {
                    \Log::error('Failed to save avatar to storage');
                }
            } else {
                \Log::error('Avatar file is not valid');
            }
        } else {
            \Log::info('No avatar file uploaded');
        }

        // Simpan perubahan
        if ($user->save()) {
            \Log::info('User saved successfully');
            \Log::info('Updated user data:', [
                'name' => $user->name,
                'avatar' => $user->avatar,
                'updated_at' => $user->updated_at
            ]);
            \Log::info('=== PROFILE UPDATE SUCCESS ===');
            
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
        } else {
            \Log::error('Failed to save user');
            \Log::info('=== PROFILE UPDATE FAILED ===');
            return redirect()->back()->with('error', 'Gagal memperbarui profil.');
        }
    }

    /**
     * Hapus avatar user
     */
    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->avatar) {
            // Hapus file dari storage
            if (Storage::exists('public/avatars/' . $user->avatar)) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            
            // Hapus dari database
            $user->avatar = null;
            $user->save();
            
            return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus!');
        }

        return redirect()->route('profile.edit')->with('error', 'Tidak ada foto profil untuk dihapus.');
    }
}