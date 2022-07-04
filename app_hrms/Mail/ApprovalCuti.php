<?php

namespace App\Mail;

use App\Models\EmployeeLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalCuti extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeLeave;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeLeave $employeeLeave, array $params)
    {
        $this->employeeLeave = $employeeLeave;
        $this->params = (object) $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (!$this->employeeLeave->id) {
            return $this->view('maileclipse::templates.approvalCuti')
                    ->with([
                        'id' => 'dummy',
                        'status' => 'dummy',
                        'atasan_name' => 'dummy',
                        'leave_cat' => 'dummy',
                    ]);
        }

        $status = $this->employeeLeave->status == 'apv' ? 'Disetujui':'Ditolak';
        $employee = $this->employeeLeave->employee;
        $leave = $this->employeeLeave->leave;

        return $this->view('maileclipse::templates.approvalCuti')
                    ->subject('Status Pengajuan '.$leave->leave_category.' ID '.$this->employeeLeave->id.' anda '.$status)
                    ->to($employee->user->email)
                    ->with([
                        'id' => $this->employeeLeave->id,
                        'status' => $status,
                        'atasan_name' => $this->params->atasan_name,
                        'leave_cat' => $leave->leave_category,
                    ]);
    }
}
