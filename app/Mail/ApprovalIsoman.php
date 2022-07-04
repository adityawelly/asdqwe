<?php

namespace App\Mail;

use App\Models\EmployeeIsoman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalIsoman extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeIsoman;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeIsoman $employeeIsoman, array $params)
    {
        $this->employeeIsoman = $employeeIsoman;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (!$this->employeeIsoman->id) {
            return $this->view('maileclipse::templates.approvalIsoman')
                    ->with([
                        'id' => 'dummy',
                        'status' => 'dummy',
                        'atasan_name' => 'dummy',
                    ]);
        }

        $status = $this->employeeIsoman->status == 'apv' ? 'Disetujui':'Ditolak';
        $employee = $this->employeeIsoman->employee;
        //$leave = $this->employeeIsoman->leave;

        return $this->view('maileclipse::templates.approvalIsoman')
                    ->subject('Status Pengajuan Isolasi Mamdiri Anda denganI D '.$this->employeeIsoman->id.' telah '.$status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->employeeIsoman->id,
                        'status' => $status,
                        'atasan_name' => $this->params->atasan_name,
                    ]);
    }
}
