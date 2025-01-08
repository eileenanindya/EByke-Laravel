<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getUserProfile()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }    

        // Debug data profil
        $profile = $user->profile;
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->profile->name,
                'email' => $user->profile->email,
                'phonenum' => $user->profile->phonenum,
                'address' => $user->profile->address,
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'phonenum'  => 'nullable|string|max:15',
            'address'   => 'nullable|string|max:255',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Ambil user yang sedang login
        $user = auth()->user();

        // Update nama di tabel users
        // $user->update([
        //     'name' => $request->name,
        // ]);

        // Update atau buat profil di tabel profiles
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->name,
                'email' => $user->email,
                'phonenum' => $request->phonenum,
                'address'  => $request->address,
            ]
        );
        $profile = $user->profile;
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'updated_profile' => $profile,
        ]);
    }
}
