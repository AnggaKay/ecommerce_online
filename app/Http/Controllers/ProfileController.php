<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil.
     * Method ini akan mengambil data user yang sedang login dan menampilkannya di view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data user yang sedang terotentikasi
        $user = Auth::user();

        // Tampilkan view 'profile.index' dan kirim data user ke dalamnya
        // Pastikan view Anda berada di 'resources/views/profile/index.blade.php' atau sesuaikan
        return view('profile.index', compact('user'));
    }

    /**
     * Memperbarui data profil dan password user.
     * Method ini akan memvalidasi dan memproses data dari form yang dikirim.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Ambil data user yang sedang terotentikasi
        $user = Auth::user();

        // 1. Validasi semua input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // Pastikan email unik, tapi abaikan untuk user saat ini
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            // Validasi password hanya jika diisi
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // 2. Update data profil utama (Nama, Email, Telepon)
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone; // Pastikan ada kolom 'phone' di tabel users Anda

        // 3. Proses perubahan password jika field password diisi
        if ($request->filled('password')) {
            // Cek apakah password saat ini yang dimasukkan benar
            if (!Hash::check($request->current_password, $user->password)) {
                // Jika salah, kembali dengan pesan error
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }

            // Jika benar, update password dengan yang baru
            $user->password = Hash::make($request->password);
        }

        // 4. Simpan semua perubahan ke database
        $user->save();

        // 5. Kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
