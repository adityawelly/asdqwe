<?php

namespace App\Mail;

use App\Models\TrainingSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalTraining extends Mailable
{
    use Queueable, SerializesModels;

    protected $trainingSubmission;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TrainingSubmission $trainingSubmission, array $params)
    {
        $this->trainingSubmission = $trainingSubmission;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->trainingSubmission->id) {
            return $this->view('maileclipse::templates.approvalTraining')
                        ->with([
                            'id' => 'dummy',
                            'atasan_name' => 'dummy',
                            'status' => 'dummy',
                        ]);
        }

        $employee = $this->trainingSubmission->submitted_by;

        return $this->view('maileclipse::templates.approvalTraining')
                    ->subject('Pengajuan Training anda ID '.$this->trainingSubmission->id.' telah di'.$this->params->status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->trainingSubmission->id,
                        'atasan_name' => $this->params->sender_name,
                        'status' => $this->params->status,
                    ]);
    }
}
