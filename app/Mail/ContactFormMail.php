<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The data from the contact form.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // di dalam app/Mail/ContactFormMail.php

public function envelope()
{
    // Ambil alamat 'From' dari konfigurasi, bukan dari input user
    $fromEmail = config('mail.from.address');
    $fromName = config('mail.from.name');

    return new Envelope(
        // PERBAIKAN: Gunakan alamat 'From' yang sudah terverifikasi
        from: new Address($fromEmail, $fromName),

        // INI SUDAH BENAR: Alamat user untuk 'Reply-To'
        replyTo: [
            new Address($this->data['email'], $this->data['name']),
        ],
        subject: 'Pesan Baru dari Form Kontak: ' . $this->data['subject'],
    );
}

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.contact-form',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
