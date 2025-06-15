<?php 

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KaryawanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $isUpdate;

    public function __construct(User $user, $isUpdate = false)
    {
        $this->user = $user;
        $this->isUpdate = $isUpdate;
    }

    public function build()
    {
        $subject = $this->isUpdate ? 'Informasi Data Karyawan' : 'Akun Karyawan';

        return $this->subject($subject)
                    ->view('emails.karyawan_baru');
    }
}