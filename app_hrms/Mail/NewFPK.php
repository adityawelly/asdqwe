<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFPK extends Mailable
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
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!property_exists($this->params, 'email')) {
            return $this->view('maileclipse::templates.newFPK')
                        ->with([
                            'RequestorName' => 'dummy',
                            'ReqNo' => 'dummy',
                        ]);
        }

        return $this->view('maileclipse::templates.newFPK')
                    ->subject('Silahkan Approval Pengajuan FPK No '.$this->params->ReqNo)
                    ->to($this->params->email)
                    ->with([
                        'RequestorName' => $this->params->RequestorName,
                        'ReqNo' => $this->params->ReqNo,
                    ]);
    }
}
