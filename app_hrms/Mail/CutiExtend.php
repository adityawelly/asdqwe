<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CutiExtend extends Mailable
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
            return $this->view('maileclipse::templates.notifCutiExtend')
                        ->with([
                            'fullname' => 'dummy',
                            'msg' => 'dummy'
                        ]);
        }

        return $this->view('maileclipse::templates.notifCutiExtend')
                    ->subject($this->params->subject)
                    ->to($this->user->email)
                    ->with([
                        'fullname' => $this->params->fullname,
                        'msg' => $this->params->msg
                    ]);
    }
}
