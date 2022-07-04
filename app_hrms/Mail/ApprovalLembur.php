<?php

namespace App\Mail;

use App\Models\EmployeeLembur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalLembur extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeLembur;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeLembur $employeeLembur, array $params)
    {
        $this->employeeLembur = $employeeLembur;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (!$this->employeeLembur->id) {
            return $this->view('maileclipse::templates.approvalDinas')
                    ->with([
                        'id' => 'dummy',
                        'status' => 'dummy',
                        'atasan_name' => 'dummy',
                    ]);
        }

        $status = $this->employeeLembur->status == 'apv' ? 'Disetujui':'Ditolak';
        $employee = $this->employeeLembur->employee;
        //$leave = $this->employeeLembur->leave;

        return $this->view('maileclipse::templates.approvalLembur')
                    ->subject('Status Pengajuan Lembur Anda dengan ID '.$this->employeeLembur->id.' telah '.$status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->employeeLembur->id,
                        'status' => $status,
                        'atasan_name' => $this->params->atasan_name,
                    ]);
    }
}
