<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// App\Mail\PeminjamanDisetujuiMail.php

class PeminjamanDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('Peminjaman Buku Disetujui')
                    ->view('emails.peminjaman_disetujui');
    }
}

