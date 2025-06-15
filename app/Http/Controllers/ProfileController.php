<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // Untuk aturan password yang lebih kuat

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profil.edit');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user(); // Dapatkan pengguna yang sedang login

        // Validasi data dasar
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik, kecuali untuk user ini sendiri
            ],
        ]);

        // Update nama dan email
        $user->name = $request->name;
        $user->email = $request->email;

        // Validasi dan update password (jika diisi)
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password lama yang Anda masukkan salah.');
                    }
                }],
                'password' => [
                    'required',
                    'confirmed', // Memastikan 'password' sama dengan 'password_confirmation'
                    Password::min(8) // Aturan password default Laravel (bisa dikustomisasi)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(), // Cek apakah password pernah bocor (memerlukan koneksi internet)
                ],
            ]);
            $user->password = Hash::make($request->password);
        } elseif ($request->filled('current_password') && !$request->filled('password')) {
             // Jika hanya isi password lama tapi password baru kosong
            return redirect()->back()->withErrors(['password' => 'Password baru tidak boleh kosong jika Anda ingin mengubah password.'])->withInput();
        }


        $user->save();

        return redirect()->route('profil.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}