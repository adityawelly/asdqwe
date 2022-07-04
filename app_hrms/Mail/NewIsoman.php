<?php

namespace App\Mail;

use App\Models\EmployeeIsoman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewIsoman extends Mailable
{
    use Queueable, SerializesModels;

    protected $employeeIsoman;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeIsoman $employeeIsoman)
    {
        $this->employeeIsoman = $employeeIsoman;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->employeeIsoman->id) {
            return $this->view('maileclipse::templates.newIsoman')
                        ->with([
                            'requestor_name' => 'dummy',
                            'start_date' => 'dummy',
                            'end_date' => 'dummy',
                            'total_days' => 'dummy',
                        ]);
        }

        $employee = $this->employeeIsoman->employee;
        $atasan = $employee->superior;
        //$leave = $this->employeeIsoman->leave;

        return $this->view('maileclipse::templates.newIsoman')
                    ->subject('Pengajuan Izin Isolasi Mandiri oleh '.$employee->fullname.' menunggu approval')
                    ->to($atasan->user->email)
                    ->with([
                      
                        'requestor_name' => $employee->fullname,
                        'start_date' => $this->employeeIsoman->start_date->format('d-m-Y'),
                        'end_date' => $this->employeeIsoman->end_date->format('d-m-Y'),
                        'total_days' => $this->employeeIsoman->total,
                    ]);
    }
}
