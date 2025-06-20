<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Menampilkan halaman form kontak.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ganti 'kontak' sesuai dengan nama file view Anda, misal: 'contact.index'
        return view('contact.index');
    }

    /**
     * Memproses pesan dari form kontak dan mengirim email sungguhan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // 2. Kirim email sungguhan
        try {
            // Ganti alamat email penerima ke alamat email Anda yang sebenarnya.
            $recipientEmail = 'grouthgame@gmail.com';

            Mail::to($recipientEmail)->send(new ContactFormMail($validatedData));

        } catch (\Exception $e) {
            // Jika gagal (misal: domain belum terverifikasi), kembali dengan pesan error
            // Untuk debugging: \Log::error('Mail Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.');
        }

        // 3. Jika berhasil, kembali ke halaman kontak dengan pesan sukses
        // Pesan disesuaikan karena email sekarang benar-benar terkirim.
        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi kami.');
    }
}
