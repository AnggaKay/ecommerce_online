<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Menampilkan daftar alamat milik user.
     */
public function index()
{
    // Ambil user yang sedang login
    $user = Auth::user();

    // Ambil alamat milik user tersebut
    $addresses = $user->addresses()->orderByDesc('is_default')->get();

    // Kirim KEDUA variabel ($user dan $addresses) ke view
    return view('profile.alamat', compact('user', 'addresses'));
}

    /**
     * Menyimpan alamat baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Jika alamat baru dijadikan default, non-aktifkan default yang lama.
        if ($request->has('is_default') && $request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($validated);

        return redirect()->route('alamat')->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui alamat yang sudah ada.
     */
    public function update(Request $request, Address $address)
    {
        // Pastikan user hanya bisa mengedit alamat miliknya sendiri
        $this->authorize('update', $address);

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        // Jika alamat ini dijadikan default, non-aktifkan default yang lama.
        if ($request->has('is_default') && $request->is_default) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('alamat')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat.
     */
    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->route('alamat')->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Menjadikan sebuah alamat sebagai default.
     */
    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);

        $user = Auth::user();
        // Set semua alamat lain menjadi tidak default
        $user->addresses()->update(['is_default' => false]);

        // Set alamat yang dipilih menjadi default
        $address->update(['is_default' => true]);

        return redirect()->route('alamat')->with('success', 'Alamat utama berhasil diubah.');
    }
}
