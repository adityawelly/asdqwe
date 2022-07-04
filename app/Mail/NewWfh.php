<?php

namespace App\Mail;

use App\Models\EmployeeWfh;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewWfh extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeWfh;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeWfh $employeeWfh)
    {
        $this->employeeWfh = $employeeWfh;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->employeeWfh->id) {
            return $this->view('maileclipse::templates.newWfh')
                        ->with([
                            'requestor_name' => 'dummy',
                            'start_date' => 'dummy',
                            'end_date' => 'dummy',
                            'total_days' => 'dummy',
                        ]);
        }

        $employee = $this->employeeWfh->employee;
        $atasan = $employee->superior;
        //$leave = $this->employeeWfh->leave;

        return $this->view('maileclipse::templates.newWfh')
                    ->subject('Pengajuan Kerja Dari Rumah oleh '.$employee->fullname.' menunggu approval')
                    ->to($atasan->user->email)
                    ->with([
                      
                        'requestor_name' => $employee->fullname,
                        'start_date' => $this->employeeWfh->start_date->format('d-m-Y'),
                        'end_date' => $this->employeeWfh->end_date->format('d-m-Y'),
                        'total_days' => $this->employeeWfh->total,
                    ]);
    }
}
