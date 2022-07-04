<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalFPK extends Mailable
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
            return $this->view('maileclipse::templates.approvalFPK')
                        ->with([
                            'ReqNo' => 'dummy',
                            'ApprovalSts' => 'dummy',
                            'ApprovalBy' => 'dummy',
                        ]);
        }

        return $this->view('maileclipse::templates.approvalFPK')
                    ->subject('Status Pengajuan FPK No '.$this->params->ReqNo)
                    ->to($this->params->email)
                    ->with([
                        'ReqNo' => $this->params->ReqNo,
                        'ApprovalSts' => $this->params->ApprovalSts == 1 ? 'APPROVE':'CANCEL',
                        'ApprovalBy' => $this->params->ApprovalBy,
                    ]);
    }
}
