<?php

namespace App\Mail;

use App\GlobalSetting;
use App\Models\TrainingSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTraining extends Mailable
{
    use Queueable, SerializesModels;

    protected $trainingSubmission;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TrainingSubmission $trainingSubmission)
    {
        $this->trainingSubmission = $trainingSubmission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->trainingSubmission->id == null) {
            return $this->view('maileclipse::templates.newTraining')
                        ->with([
                            'first_name' => 'dummy',
                            'requestor_name' => 'dummy',
                            'request_id' => 'dummy',
                            'link' => '#',
                        ]);
        }

        $employee = $this->trainingSubmission->submitted_by;
        $atasan = $employee->superior;

        return $this->view('maileclipse::templates.newTraining')
                ->subject('Pengajuan Training Baru Oleh '.$employee->fullname)
                ->to($atasan->user->email)
                ->with([
                    'first_name' => $atasan->fullname,
                    'requestor_name' => $employee->fullname,
                    'request_id' => $this->trainingSubmission->id,
                    'link' => route('pengajuan.training.approval'),
                ]);
    }
}
