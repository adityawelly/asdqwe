<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PKWT extends Mailable
{
    use Queueable, SerializesModels;

    protected $params;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $params, User $user)
    {
        $this->params = (object) $params;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!property_exists($this->params, 'fullname')) {
            return $this->view('maileclipse::templates.notifPKWT')
                        ->with([
                            'fullname' => 'dummy',
                            'msg' => 'dummy'
                        ]);
        }

        return $this->view('maileclipse::templates.notifPKWT')
                    ->subject($this->params->subject)
                    ->to($this->user->email)
                    ->with([
                        'fullname' => $this->params->fullname,
                        'msg' => $this->params->msg
                    ]);
    }
}
