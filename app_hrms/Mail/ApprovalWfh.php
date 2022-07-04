<?php

namespace App\Mail;

use App\Models\EmployeeWfh;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalWfh extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeWfh;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeWfh $employeeWfh, array $params)
    {
        $this->employeeWfh = $employeeWfh;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (!$this->employeeWfh->id) {
            return $this->view('maileclipse::templates.approvalWfh')
                    ->with([
                        'id' => 'dummy',
                        'status' => 'dummy',
                        'atasan_name' => 'dummy',
                    ]);
        }

        $status = $this->employeeWfh->status == 'apv' ? 'Disetujui':'Ditolak';
        $employee = $this->employeeWfh->employee;
        //$leave = $this->employeeWfh->leave;

        return $this->view('maileclipse::templates.approvalWfh')
                    ->subject('Status Pengajuan Kerja Dari Rumah Anda denganI D '.$this->employeeWfh->id.' telah '.$status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->employeeWfh->id,
                        'status' => $status,
                        'atasan_name' => $this->params->atasan_name,
                    ]);
    }
}
