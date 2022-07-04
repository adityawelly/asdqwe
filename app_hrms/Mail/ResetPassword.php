<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        if (count($params) > 0) {
            $this->params = (object) $params;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->params) {
            return $this->view('maileclipse::templates.resetPassword')->with([
                'link' => url('/'),
                'new_password' => 'dummy',
            ]);
        }

        return $this->view('maileclipse::templates.resetPassword')
                ->to($this->params->email)
                ->subject('Password Akun Anda Telah Direset')
                ->with([
                    'link' => url('/'),
                    'new_password' => $this->params->new_password,
                ]);
    }
}
