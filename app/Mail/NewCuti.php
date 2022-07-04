<?php

namespace App\Mail;

use App\Models\EmployeeLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCuti extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeLeave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeLeave $employeeLeave)
    {
        $this->employeeLeave = $employeeLeave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->employeeLeave->id) {
            return $this->view('maileclipse::templates.newCuti')
                        ->with([
                            'cuti_name' => 'dummy',
                            'requestor_name' => 'dummy',
                            'start_date' => 'dummy',
                            'end_date' => 'dummy',
                            'work_days' => 'dummy',
                            'total_days' => 'dummy',
                            'leave_cat' => 'dummy',
                        ]);
        }

        $employee = $this->employeeLeave->employee;
        $atasan = $employee->superior;
        $leave = $this->employeeLeave->leave;

        return $this->view('maileclipse::templates.newCuti')
                    ->subject('Pengajuan '.$leave->leave_category.' oleh '.$employee->fullname.' menunggu approval')
                    ->to($atasan->user->email)
                    ->with([
                        'cuti_name' => $this->employeeLeave->leave->leave_name,
                        'requestor_name' => $employee->fullname,
                        'start_date' => $this->employeeLeave->start_date->format('d-m-Y'),
                        'end_date' => $this->employeeLeave->end_date->format('d-m-Y'),
                        'work_days' => $this->employeeLeave->total,
                        'total_days' => $this->employeeLeave->diff_days,
                        'leave_cat' => $leave->leave_category,
                    ]);
    }
}
