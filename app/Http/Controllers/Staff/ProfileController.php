<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('staff.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photo_base64' => 'nullable|string',
        ]);

        $data = $request->only('name', 'email', 'phone', 'address');

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('staff-photos', 'public');
        } elseif ($request->filled('photo_base64')) {
            // Handle base64 camera capture
            $base64 = $request->photo_base64;
            if (preg_match('/^data:image\/(jpeg|png|jpg);base64,/', $base64, $matches)) {
                $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
                $imageData = base64_decode($base64);
                $filename = 'staff-photos/' . uniqid('cam_') . '.' . $ext;
                Storage::disk('public')->put($filename, $imageData);
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }
                $data['photo'] = $filename;
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
